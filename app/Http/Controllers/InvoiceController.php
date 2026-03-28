<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\StockUsage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    /**
     * Download Invoice PDF
     */
    public function download($id)
    {
        // Find invoice by usage_id or invoice_id
        $invoice = Invoice::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['stock', 'user', 'usage'])
            ->first();

        if (!$invoice) {
            // Try by usage_id as fallback
            $invoice = Invoice::where('usage_id', $id)
                ->where('user_id', Auth::id())
                ->with(['stock', 'user', 'usage'])
                ->first();
        }

        if (!$invoice) {
            abort(404, 'Invoice not found.');
        }

        // Generate QR code if user has a payment_id
        $qrCodeBase64 = null;
        $paymentId = $invoice->user->payment_id ?? null;
        $currency = $invoice->user->currency ?? 'USD';

        // Currency symbol map
        $currencySymbols = [
            'INR' => '₹',
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
        ];
        $currencySymbol = $currencySymbols[$currency] ?? '$';

        // Payment label map
        $paymentLabels = [
            'INR' => 'UPI',
            'USD' => 'PayPal',
            'GBP' => 'Revolut / Bank',
            'EUR' => 'SEPA / PayPal',
        ];
        $paymentLabel = $paymentLabels[$currency] ?? 'Payment ID';

        if ($paymentId) {
            // Build QR code data based on currency
            if ($currency === 'INR') {
                // UPI payment URI format
                $businessName = urlencode($invoice->user->business_name ?? 'StockProNex');
                $amount = number_format($invoice->amount, 2, '.', '');
                $qrData = "upi://pay?pa={$paymentId}&pn={$businessName}&am={$amount}&cu=INR&tn=Invoice%20{$invoice->invoice_number}";
            } else {
                // For other currencies, encode payment details as text
                $qrData = "Pay {$currencySymbol}{$invoice->amount} to {$paymentId} | Invoice: {$invoice->invoice_number}";
            }

            // Generate QR code as SVG string (DomPDF supports inline SVG)
            $qrSvg = QrCode::format('svg')
                ->size(150)
                ->margin(1)
                ->generate($qrData);

            // Convert SVG to base64 for embedding in PDF
            $qrCodeBase64 = base64_encode($qrSvg);
        }

        $pdf = Pdf::loadView('invoice.pdf', compact(
            'invoice',
            'qrCodeBase64',
            'paymentId',
            'paymentLabel',
            'currencySymbol',
            'currency'
        ))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans'
            ]);
        
        return $pdf->download($invoice->invoice_number . '.pdf');
    }
}
