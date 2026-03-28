<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\WhatsappLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WhatsappService
{
    /**
     * Send invoice via WhatsApp Cloud API — SYNCHRONOUS, NO EARLY RETURNS
     *
     * Flow:
     * 1. Generate invoice PDF → save to disk
     * 2. Send template message (invoice_notification)
     * 3. Upload PDF to WhatsApp Media API → get media_id
     * 4. Send document message using media_id
     * 5. If document fails → fallback to text message with invoice details
     */
    public function sendInvoiceOnWhatsApp(Invoice $invoice): array
    {
        $phoneNumberId = config('services.whatsapp.phone_number_id');
        $accessToken = config('services.whatsapp.access_token');

        if (!$phoneNumberId || !$accessToken) {
            Log::error('WhatsApp: API credentials missing');
            return ['success' => false, 'message' => 'WhatsApp API credentials not configured.'];
        }

        $phone = self::formatWhatsappNumber($invoice->phone);

        Log::info('=== WhatsApp: BEGIN SEND ===', [
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'raw_phone' => $invoice->phone,
            'formatted_phone' => $phone,
        ]);

        if (!$phone) {
            Log::warning('WhatsApp: Invalid phone', ['raw' => $invoice->phone]);
            return ['success' => false, 'message' => 'Invalid or missing phone number.'];
        }

        // Create log entry
        $log = WhatsappLog::create([
            'invoice_id' => $invoice->id,
            'user_id' => $invoice->user_id,
            'phone' => $phone,
            'status' => 'pending',
        ]);

        $apiBase = "https://graph.facebook.com/v22.0/{$phoneNumberId}";
        $results = ['template' => null, 'pdf_generated' => false, 'media_uploaded' => false, 'document_sent' => false, 'text_sent' => false];

        // ─────────────────────────────────────────
        // STEP 1: Generate PDF
        // ─────────────────────────────────────────
        $pdfPath = null;
        $pdfFilename = null;
        try {
            $invoice->load(['stock', 'user', 'usage']);

            $qrCodeBase64 = null;
            $paymentId = $invoice->user->payment_id ?? null;
            $currency = $invoice->user->currency ?? 'USD';
            $currencySymbols = ['INR' => '₹', 'USD' => '$', 'GBP' => '£', 'EUR' => '€'];
            $currencySymbol = $currencySymbols[$currency] ?? '$';
            $paymentLabels = ['INR' => 'UPI', 'USD' => 'PayPal', 'GBP' => 'Revolut / Bank', 'EUR' => 'SEPA / PayPal'];
            $paymentLabel = $paymentLabels[$currency] ?? 'Payment ID';

            if ($paymentId) {
                if ($currency === 'INR') {
                    $bn = urlencode($invoice->user->business_name ?? 'StockProNex');
                    $amt = number_format($invoice->amount, 2, '.', '');
                    $qrData = "upi://pay?pa={$paymentId}&pn={$bn}&am={$amt}&cu=INR&tn=Invoice%20{$invoice->invoice_number}";
                } else {
                    $qrData = "Pay {$currencySymbol}{$invoice->amount} to {$paymentId} | Invoice: {$invoice->invoice_number}";
                }
                $qrSvg = QrCode::format('svg')->size(150)->margin(1)->generate($qrData);
                $qrCodeBase64 = base64_encode($qrSvg);
            }

            $pdf = Pdf::loadView('invoice.pdf', compact(
                'invoice', 'qrCodeBase64', 'paymentId', 'paymentLabel', 'currencySymbol', 'currency'
            ))->setPaper('A4', 'portrait')->setOptions(['dpi' => 150, 'defaultFont' => 'DejaVu Sans']);

            $pdfFilename = $invoice->invoice_number . '.pdf';
            $storagePath = 'invoices/' . $pdfFilename;
            Storage::disk('public')->put($storagePath, $pdf->output());
            $pdfPath = Storage::disk('public')->path($storagePath);
            $results['pdf_generated'] = true;

            Log::info('WhatsApp: STEP 1 OK — PDF generated', ['path' => $pdfPath, 'size' => filesize($pdfPath)]);
        } catch (\Exception $e) {
            Log::error('WhatsApp: STEP 1 FAIL — PDF generation error', ['error' => $e->getMessage()]);
            // Continue — we can still send template + text fallback
        }

        // ─────────────────────────────────────────
        // STEP 2: Send template message
        // ─────────────────────────────────────────
        try {
            $currencySymbol = $currencySymbol ?? '₹';
            $totalAmount = number_format($invoice->total_amount ?? $invoice->amount, 2);

            $templateName = config('services.whatsapp.template_name', 'hello_world');
            $templateLang = $templateName === 'hello_world' ? 'en_US' : 'en';

            $templateConfig = [
                'name' => $templateName,
                'language' => ['code' => $templateLang],
            ];

            // Add body parameters only for custom templates (not hello_world)
            if ($templateName !== 'hello_world') {
                $templateConfig['components'] = [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $invoice->invoice_number],
                            ['type' => 'text', 'text' => $currencySymbol . $totalAmount],
                        ],
                    ],
                ];
            }

            $templatePayload = [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'template',
                'template' => $templateConfig,
            ];

            Log::info('WhatsApp: STEP 2 — Sending template', ['payload' => $templatePayload]);

            $templateResponse = Http::withToken($accessToken)->timeout(30)->post("{$apiBase}/messages", $templatePayload);
            $templateData = $templateResponse->json();

            Log::info('WhatsApp: STEP 2 — Template response', [
                'http_status' => $templateResponse->status(),
                'body' => $templateData,
            ]);

            if ($templateResponse->status() === 200 && isset($templateData['messages'][0]['id'])) {
                $results['template'] = $templateData['messages'][0]['id'];
                Log::info('WhatsApp: STEP 2 OK — Template sent', ['message_id' => $results['template']]);
            } else {
                $errMsg = $templateData['error']['message'] ?? 'HTTP ' . $templateResponse->status();
                Log::error('WhatsApp: STEP 2 FAIL — Template rejected', ['error' => $errMsg, 'body' => $templateData]);

                $log->update(['status' => 'failed', 'response_json' => json_encode($templateData)]);
                return ['success' => false, 'message' => 'Template send failed: ' . $errMsg, 'log_id' => $log->id];
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp: STEP 2 EXCEPTION', ['error' => $e->getMessage()]);
            $log->update(['status' => 'failed', 'response_json' => json_encode(['exception' => $e->getMessage()])]);
            return ['success' => false, 'message' => 'Template send failed: ' . $e->getMessage(), 'log_id' => $log->id];
        }

        // ─────────────────────────────────────────
        // STEP 3: Upload PDF to WhatsApp Media API
        // ─────────────────────────────────────────
        $mediaId = null;
        if ($pdfPath && file_exists($pdfPath)) {
            try {
                Log::info('WhatsApp: STEP 3 — Uploading PDF to Media API', ['file' => $pdfPath]);

                $mediaResponse = Http::withToken($accessToken)
                    ->timeout(60)
                    ->attach('file', file_get_contents($pdfPath), $pdfFilename, ['Content-Type' => 'application/pdf'])
                    ->post("{$apiBase}/media", [
                        'messaging_product' => 'whatsapp',
                        'type' => 'application/pdf',
                    ]);

                $mediaData = $mediaResponse->json();

                Log::info('WhatsApp: STEP 3 — Media upload response', [
                    'http_status' => $mediaResponse->status(),
                    'body' => $mediaData,
                ]);

                if ($mediaResponse->status() === 200 && isset($mediaData['id'])) {
                    $mediaId = $mediaData['id'];
                    $results['media_uploaded'] = true;
                    Log::info('WhatsApp: STEP 3 OK — Media uploaded', ['media_id' => $mediaId]);
                } else {
                    Log::error('WhatsApp: STEP 3 FAIL — Media upload rejected', ['body' => $mediaData]);
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp: STEP 3 EXCEPTION', ['error' => $e->getMessage()]);
            }
        } else {
            Log::warning('WhatsApp: STEP 3 SKIP — No PDF file available');
        }

        // ─────────────────────────────────────────
        // STEP 4: Send document message using media_id
        // ─────────────────────────────────────────
        if ($mediaId) {
            try {
                $docPayload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'document',
                    'document' => [
                        'id' => $mediaId,
                        'filename' => $pdfFilename,
                    ],
                ];

                Log::info('WhatsApp: STEP 4 — Sending document', ['payload' => $docPayload]);

                $docResponse = Http::withToken($accessToken)->timeout(30)->post("{$apiBase}/messages", $docPayload);
                $docData = $docResponse->json();

                Log::info('WhatsApp: STEP 4 — Document response', [
                    'http_status' => $docResponse->status(),
                    'body' => $docData,
                ]);

                if ($docResponse->status() === 200 && isset($docData['messages'][0]['id'])) {
                    $results['document_sent'] = true;
                    Log::info('WhatsApp: STEP 4 OK — Document sent', ['message_id' => $docData['messages'][0]['id']]);
                } else {
                    $errMsg = $docData['error']['message'] ?? 'Unknown';
                    Log::error('WhatsApp: STEP 4 FAIL — Document rejected', ['error' => $errMsg, 'body' => $docData]);
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp: STEP 4 EXCEPTION', ['error' => $e->getMessage()]);
            }
        } else {
            Log::warning('WhatsApp: STEP 4 SKIP — No media_id available');
        }

        // ─────────────────────────────────────────
        // STEP 5: Fallback — text message with invoice details
        // ─────────────────────────────────────────
        if (!$results['document_sent']) {
            try {
                $businessName = $invoice->user->business_name ?? 'StockProNex';
                $symbol = $currencySymbol ?? '₹';
                $amount = number_format($invoice->total_amount ?? $invoice->amount, 2);

                $textBody = "Hello {$invoice->customer_name},\n\n"
                    . "Thank you for your purchase from *{$businessName}*!\n\n"
                    . "📋 *Invoice #{$invoice->invoice_number}*\n"
                    . "💰 Amount: *{$symbol}{$amount}*\n"
                    . "💳 Payment: " . ucfirst($invoice->payment_method ?? 'cash') . "\n\n"
                    . "Thank you for your business! 🙏";

                $textPayload = [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => ['preview_url' => false, 'body' => $textBody],
                ];

                Log::info('WhatsApp: STEP 5 — Sending text fallback');

                $textResponse = Http::withToken($accessToken)->timeout(30)->post("{$apiBase}/messages", $textPayload);
                $textData = $textResponse->json();

                Log::info('WhatsApp: STEP 5 — Text response', [
                    'http_status' => $textResponse->status(),
                    'body' => $textData,
                ]);

                if ($textResponse->status() === 200 && isset($textData['messages'][0]['id'])) {
                    $results['text_sent'] = true;
                    Log::info('WhatsApp: STEP 5 OK — Text sent');
                } else {
                    Log::error('WhatsApp: STEP 5 FAIL — Text rejected', ['body' => $textData]);
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp: STEP 5 EXCEPTION', ['error' => $e->getMessage()]);
            }
        }

        // ─────────────────────────────────────────
        // FINAL: Update log and return
        // ─────────────────────────────────────────
        Log::info('=== WhatsApp: END SEND ===', $results);

        $log->update([
            'status' => 'sent',
            'response_json' => json_encode($results),
        ]);

        $message = 'Invoice notification sent on WhatsApp.';
        if ($results['document_sent']) {
            $message = 'Invoice PDF sent successfully on WhatsApp.';
        } elseif ($results['text_sent']) {
            $message = 'Invoice details sent on WhatsApp (text).';
        }

        return [
            'success' => true,
            'message' => $message,
            'log_id' => $log->id,
        ];
    }

    /**
     * Format phone to WhatsApp format: country code + number, no +, no spaces
     */
    public static function formatWhatsappNumber(?string $phone): ?string
    {
        if (!$phone || $phone === 'N/A') return null;

        $cleaned = preg_replace('/[^\d]/', '', $phone);
        $cleaned = ltrim($cleaned, '0');

        if (strlen($cleaned) < 10) return null;
        if (strlen($cleaned) === 10) $cleaned = '91' . $cleaned;

        Log::info('WhatsApp: Phone formatted', ['original' => $phone, 'formatted' => $cleaned]);
        return $cleaned;
    }
}
