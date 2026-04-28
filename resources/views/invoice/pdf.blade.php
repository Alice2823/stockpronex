<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        
        /* Layout */
        .page-accent {
            height: 8px;
            background-color: {{ $invoice->user->invoice_color ?? '#2563eb' }};
            width: 100%;
        }
        .container {
            padding: 20px 40px;
        }
        
        /* Colors */
        .text-blue { color: {{ $invoice->user->invoice_color ?? '#2563eb' }}; }
        .text-gray { color: #64748b; }
        .bg-light { background-color: #f8fafc; }
        
        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .brand-name {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -1px;
        }
        .brand-tagline {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .invoice-label {
            font-size: 28px;
            font-weight: 900;
            color: #e2e8f0;
            text-align: right;
            line-height: 1;
        }

        /* Info Section */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .info-table td {
            vertical-align: top;
            padding: 20px;
            border: 1px solid #f1f5f9;
        }
        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .info-text {
            font-size: 13px;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .info-bold {
            font-weight: 700;
            color: #0f172a;
        }

        /* Items Table */
        .items-title {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            border-left: 4px solid {{ $invoice->user->invoice_color ?? '#2563eb' }};
            padding-left: 10px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 800;
            text-align: left;
            padding: 12px 15px;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }
        .items-table td {
            padding: 10px 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        
        /* Totals */
        .totals-table {
            width: 300px;
            margin-top: 15px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-row td {
            padding: 8px 0;
        }
        .total-label {
            text-align: right;
            color: #64748b;
            padding-right: 20px !important;
        }
        .total-value {
            text-align: right;
            font-weight: 700;
            width: 120px;
        }
        .grand-total-row td {
            padding-top: 15px;
            border-top: 2px solid #f1f5f9;
        }
        .grand-total-label {
            font-size: 16px;
            font-weight: 800;
            color: #1e293b;
        }
        .grand-total-amount {
            font-size: 18px;
            font-weight: 800;
            color: {{ $invoice->user->invoice_color ?? '#2563eb' }};
        }

        /* QR Code Payment Section */
        .payment-section {
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background-color: #f8fafc;
            page-break-inside: avoid;
        }
        .payment-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .payment-title {
            font-size: 14px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .payment-subtitle {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .qr-container {
            text-align: center;
            padding: 10px 0;
        }
        .qr-container img {
            width: 120px;
            height: 120px;
        }
        .payment-id-display {
            text-align: center;
            margin-top: 10px;
            padding: 8px 15px;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            display: inline-block;
        }
        .payment-id-label {
            font-size: 9px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .payment-id-value {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2px;
        }
        .scan-text {
            font-size: 10px;
            color: {{ $invoice->user->invoice_color ?? '#2563eb' }};
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .footer-inner {
            padding: 25px 50px;
        }
        .footer-content { color: #64748b; font-size: 11px; }
        .footer-thankyou { 
            font-weight: 800; 
            color: #1e293b; 
            margin-bottom: 5px; 
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="page-accent"></div>
    
    <div class="container">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand-name">
                        <span style="color: #0f172a;">{{ $invoice->user->business_name ?? 'StockProNex' }}</span>
                    </div>
                    <div class="brand-tagline">{{ $invoice->user->address ?? 'Professional Inventory Solutions' }}</div>
                    @if($invoice->user->tax_number)
                        <div class="brand-tagline" style="margin-top: 3px;">
                            {{ $invoice->user->currency === 'INR' ? 'GST Number: ' : 'Tax/VAT: ' }}{{ $invoice->user->tax_number }}
                        </div>
                    @endif
                    @if($invoice->user->phone)
                        <div class="brand-tagline" style="margin-top: 3px;">
                            Contact: {{ $invoice->user->phone }}
                        </div>
                    @endif
                </td>
                <td class="invoice-label">
                    INVOICE
                </td>
            </tr>
        </table>

        <!-- Info Grid -->
        <table class="info-table">
            <tr>
                <td class="bg-light">
                    <div class="section-title">Billed To</div>
                    <div class="info-text info-bold">{{ $invoice->customer_name }}</div>
                    @if($invoice->company_name)
                        <div class="info-text">{{ $invoice->company_name }}</div>
                    @endif
                    <div class="info-text" style="width: 250px;">{{ $invoice->address }}</div>
                    <div class="info-text"><span class="text-gray">Phone:</span> {{ $invoice->phone }}</div>
                </td>
                <td>
                    <div class="section-title">Invoice Details</div>
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border:none; padding: 0 0 5px 0;" class="text-gray">Invoice ID:</td>
                            <td style="border:none; padding: 0 0 5px 0; text-align: right;" class="info-bold">#{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td style="border:none; padding: 0 0 5px 0;" class="text-gray">Date:</td>
                            <td style="border:none; padding: 0 0 5px 0; text-align: right;" class="info-bold">{{ $invoice->created_at->setTimezone('Asia/Kolkata')->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border:none; padding: 0 0 5px 0;" class="text-gray">Time:</td>
                            <td style="border:none; padding: 0 0 5px 0; text-align: right;" class="info-bold">{{ $invoice->created_at->setTimezone('Asia/Kolkata')->format('h:i A') }}</td>
                        </tr>
                        <tr>
                            <td style="border:none; padding: 15px 0 0 0;" class="text-gray">Status:</td>
                            <td style="border:none; padding: 15px 0 0 0; text-align: right;">
                                <span style="background-color: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase;">PAID</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items -->
        <div class="items-title">Transaction Summary</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th width="40%">Item Description</th>
                    <th width="20%" style="text-align: center;">Qty</th>
                    <th width="20%" style="text-align: right;">Unit Price</th>
                    <th width="20%" style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->items && is_array($invoice->items))
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>
                                <div class="info-bold">{{ $item['name'] }}</div>
                                <div class="text-gray" style="font-size: 10px; margin-top: 5px;">
                                    <div style="margin-bottom: 2px;">
                                        @if(!empty($item['brand'])) <span style="margin-right: 10px;"><strong>BRAND:</strong> {{ $item['brand'] }}</span> @endif
                                        @if(!empty($item['model'])) <span><strong>MODEL:</strong> {{ $item['model'] }}</span> @endif
                                    </div>
                                    @if(!empty($item['barcode']) && $item['barcode'] !== 'N/A')
                                    <div style="margin-bottom: 2px;">
                                        <strong>TRACKING ID:</strong> {{ $item['barcode'] }}
                                    </div>
                                    @endif
                                    @if(!empty($item['imei']) || !empty($item['serial']))
                                    <div style="margin-top: 3px; background-color: #f8fafc; padding: 4px 8px; border-radius: 4px; border: 1px solid #e2e8f0;">
                                        @if(!empty($item['imei'])) <span style="margin-right: 15px;"><strong>Serial / IMEI:</strong> {{ $item['imei'] }}</span> @endif
                                        @if(!empty($item['serial'])) <span><strong>Serial Number:</strong> {{ $item['serial'] }}</span> @endif
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center;" class="info-bold">1</td>
                            <td style="text-align: right;" class="text-gray">{{ $currencySymbol }}&nbsp;{{ number_format($item['unit_price'], 2) }}</td>
                            <td style="text-align: right;" class="info-bold">{{ $currencySymbol }}&nbsp;{{ number_format($item['unit_price'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>
                            <div class="info-bold">{{ $invoice->stock->name }}</div>
                            <div class="text-gray" style="font-size: 11px; margin-top: 4px;">
                                @php
                                    $usageNotes = $invoice->usage->notes ?? '';
                                    // Extract barcodes if multiple
                                    $barcodes = [];
                                    if (str_contains($usageNotes, 'Tracked Items:')) {
                                        $parts = explode('|', $usageNotes);
                                        $trackedPart = trim($parts[0]);
                                        $barcodeList = str_replace('Tracked Items:', '', $trackedPart);
                                        $barcodes = array_map('trim', explode(',', $barcodeList));
                                    }
                                    
                                    $hasUnitDetails = str_contains($usageNotes, 'IMEI:') || str_contains($usageNotes, 'SERIAL:') || str_contains($usageNotes, 'BATCH:');
                                    $qty = abs($invoice->usage->quantity ?? 1);
                                    $unitPrice = ($invoice->subtotal ?? $invoice->amount) / ($qty ?: 1);
                                @endphp

                                @if(count($barcodes) > 0)
                                    <div style="margin-bottom: 5px;">
                                        <strong style="color: #475569;">Tracked Items:</strong> {{ implode(', ', $barcodes) }}
                                    </div>
                                @endif

                                @if($hasUnitDetails)
                                    <div style="margin-top: 2px;">
                                        @foreach(explode("\n", $usageNotes) as $line)
                                            @php
                                                $lowerLine = strtolower($line);
                                            @endphp
                                            @if(str_contains($lowerLine, 'item #') || str_contains($lowerLine, 'imei:') || str_contains($lowerLine, 'serial:') || str_contains($lowerLine, 'batch:') || str_contains($lowerLine, 'brand:') || str_contains($lowerLine, 'model:'))
                                                <span style="display:inline-block; margin-right: 15px; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; color: #1e293b; margin-top: 2px;">
                                                    <strong style="color: #475569;">{{ trim(explode(':', $line)[0]) }}:</strong> {{ trim(implode(':', array_slice(explode(':', $line), 1))) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif($invoice->stock->business_attributes && count($invoice->stock->business_attributes) > 0)
                                    @foreach($invoice->stock->business_attributes as $key => $value)
                                        @if($value)
                                            <span style="display:inline-block; margin-right: 12px; margin-bottom: 2px;">
                                                <strong style="color: #475569;">{{ strtoupper(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                                            </span>
                                        @endif
                                    @endforeach
                                @else
                                    Verified Stock Transaction
                                @endif
                            </div>
                        </td>
                        <td style="text-align: center;" class="info-bold">{{ $qty ?? 1 }}</td>
                        <td style="text-align: right;" class="text-gray">{{ $currencySymbol }}&nbsp;{{ number_format($unitPrice ?? 0, 2) }}</td>
                        <td style="text-align: right;" class="info-bold">{{ $currencySymbol }}&nbsp;{{ number_format($invoice->subtotal ?? $invoice->amount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr class="total-row">
                <td class="total-label">Subtotal</td>
                <td class="total-value">{{ $currencySymbol }}&nbsp;{{ number_format($invoice->subtotal ?? $invoice->amount, 2) }}</td>
            </tr>
            @if($invoice->discount_percentage > 0)
            <tr class="total-row">
                <td class="total-label" style="color: #ef4444;">Discount ({{ number_format($invoice->discount_percentage, 1) }}%)</td>
                <td class="total-value" style="color: #ef4444;">-{{ $currencySymbol }}&nbsp;{{ number_format($invoice->discount_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td class="total-label">Net Amount</td>
                <td class="total-value">{{ $currencySymbol }}&nbsp;{{ number_format(($invoice->subtotal ?? $invoice->amount) - $invoice->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td class="total-label">Tax ({{ number_format($invoice->tax_percentage ?? 0, 1) }}%)</td>
                <td class="total-value">{{ $currencySymbol }}&nbsp;{{ number_format($invoice->tax_amount ?? 0, 2) }}</td>
            </tr>
            <tr class="grand-total-row">
                <td class="grand-total-label">Invoice Total</td>
                <td class="grand-total-amount">{{ $currencySymbol }}&nbsp;{{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </table>

        <!-- Payment QR Code Section -->
        @if($qrCodeBase64 && $paymentId)
            <div class="payment-section">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="180" style="text-align: center; vertical-align: top; border: none; padding: 0;">
                            <div class="qr-container">
                                <img src="data:image/svg+xml;base64,{{ $qrCodeBase64 }}" alt="Payment QR Code" style="width: 110px; height: 110px;" />
                            </div>
                            <div class="scan-text">↑ Scan to Pay ↑</div>
                        </td>
                        <td style="vertical-align: top; padding-left: 25px; border: none;">
                            <div class="payment-title">Pay Online</div>
                            <div class="payment-subtitle" style="margin-bottom: 15px;">Scan the QR code or use the payment details below</div>
                            
                            <table cellspacing="0" cellpadding="0" style="width: 100%;">
                                <tr>
                                    <td style="border: none; padding: 4px 0;">
                                        <span class="payment-id-label">{{ $paymentLabel }}:</span>
                                    </td>
                                    <td style="border: none; padding: 4px 0;">
                                        <span class="payment-id-value">{{ $paymentId }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 4px 0;">
                                        <span class="payment-id-label">Amount:</span>
                                    </td>
                                    <td style="border: none; padding: 4px 0;">
                                        <span class="payment-id-value">{{ $currencySymbol }}&nbsp;{{ number_format($invoice->amount, 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border: none; padding: 4px 0;">
                                        <span class="payment-id-label">Invoice:</span>
                                    </td>
                                    <td style="border: none; padding: 4px 0;">
                                        <span class="payment-id-value">#{{ $invoice->invoice_number }}</span>
                                    </td>
                                </tr>
                            </table>

                            <div style="margin-top: 12px; padding: 8px 12px; background-color: #eff6ff; border-radius: 6px; border: 1px solid #bfdbfe;">
                                <span style="font-size: 10px; color: #1e40af; font-weight: 600;">
                                    @if($currency === 'INR')
                                        Open any UPI app (Google Pay, PhonePe, Paytm) and scan to pay instantly.
                                    @else
                                        Use the {{ $paymentLabel }} details above or scan the QR code to complete payment.
                                    @endif
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        @if($invoice->user->bank_name && $invoice->user->account_number)
            <!-- Bank Details Section -->
            <div style="margin-top: 15px; padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; page-break-inside: avoid;">
                <div class="section-title" style="color: {{ $invoice->user->invoice_color ?? '#2563eb' }}; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 3px;">Bank Transfer Details</div>
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="33%" style="border: none; padding: 0;">
                            <div class="payment-id-label">Bank Name</div>
                            <div class="info-bold" style="font-size: 13px;">{{ $invoice->user->bank_name }}</div>
                        </td>
                        <td width="33%" style="border: none; padding: 0;">
                            <div class="payment-id-label">Account Number</div>
                            <div class="info-bold" style="font-size: 13px;">{{ $invoice->user->account_number }}</div>
                        </td>
                        <td width="33%" style="border: none; padding: 0;">
                            <div class="payment-id-label">IFSC / SWIFT Code</div>
                            <div class="info-bold" style="font-size: 13px;">{{ $invoice->user->ifsc_code ?? 'N/A' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        <!-- Bottom Section: Notes and Signature -->
        <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 15px; page-break-inside: avoid;">
            <tr>
                <td width="60%" style="border: none; padding: 0; vertical-align: top;">
                    <div style="padding: 15px; border: 1px dashed #e2e8f0; border-radius: 8px; margin-right: 15px;">
                        <div class="section-title">Important Notes</div>
                        <div class="text-gray" style="font-size: 10px;">
                            1. This invoice is a record of stock asset movement.<br>
                            2. Goods once issued are recorded in the auditing logs.<br>
                            3. For discrepancies, please contact the administrator.
                        </div>
                    </div>
                </td>
                <td width="40%" style="border: none; padding: 0; vertical-align: bottom; text-align: center;">
                    <div style="margin-top: 10px;">
                        <div style="height: 60px;"></div>
                        <div style="border-top: 1px solid #1e293b; padding-top: 5px; font-weight: 800; font-size: 11px; text-transform: uppercase; color: #1e293b;">
                            Authorized Signatory
                        </div>
                        <div style="font-size: 9px; color: #64748b; margin-top: 2px;">
                            (Seal & Signature)
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-inner">
            <table width="100%">
                <tr>
                    <td>
                        <div class="footer-thankyou">Thank you for choosing StockProNex!</div>
                        <div class="footer-content">This is a system-generated document for inventory verification.</div>
                    </td>
                    <td style="text-align: right; vertical-align: bottom;">
                        <div class="footer-content">&copy; {{ date('Y') }} StockProNex • www.stockpronex.com</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
