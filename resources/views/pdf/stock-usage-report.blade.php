<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ __('Stock Usage Report') }} - {{ date('d M Y') }}</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #222;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        h1 { font-size: 28px; }
        h2 { font-size: 22px; }
        h3 { font-size: 18px; }
        p { font-size: 14px; }
        
        .header {
            padding: 30px 40px;
            border-bottom: 2px solid #e2e8f0;
        }
        .container {
            padding: 30px 40px;
        }
        .report-header {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
        }
        .report-subtitle {
            font-size: 16px;
            color: #64748b;
        }
        .report-date {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
        }
        .brand-stock { color: #1f2937; font-weight: bold; }
        .brand-pro { color: #0d6efd; font-weight: bold; }
        .brand-nex { color: #1f2937; font-weight: bold; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 20px;
        }
        table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #e2e8f0;
            font-size: 15px;
            text-transform: uppercase;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: top;
        }
        .small-text {
            font-size: 13px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 15px 0;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
        tr:nth-child(even) {
            background-color: #fcfcfc;
        }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <div class="report-header">
                        {{ auth()->user()->business_name ?? 'StockProNex' }}
                    </div>
                    <div class="report-subtitle">
                        {{ auth()->user()->address ?? __('Inventory Management System') }}
                    </div>
                </td>
                <td align="right" style="vertical-align: bottom;">
                    <div class="report-subtitle" style="font-weight: bold; color: #1e293b;">
                        {{ __('STOCK USAGE REPORT') }}
                    </div>
                    <div class="report-date">
                        {{ __('Generated on:') }} {{ now()->format('d M Y, h:i A') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <hr style="border: 1px solid #ddd; margin-top: 10px; margin-bottom: 20px;">

    <div class="container">
        <table width="100%">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Product Name') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Customer') }}</th>
                    <th>{{ __('Notes') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usages as $usage)
                <tr>
                    <td class="small-text">{{ $usage->created_at->format('d M Y') }}</td>
                    <td><strong>{{ $usage->stock->name }}</strong></td>
                    <td style="font-weight: bold;">{{ number_format($usage->quantity) }}</td>
                    <td>
                        @if($usage->invoice)
                            <strong>{{ $usage->invoice->customer_name }}</strong><br>
                            <span class="small-text" style="color: #64748b;">Inv: {{ $usage->invoice->invoice_number }}</span>
                        @else
                            {{ __('Internal / Manual') }}
                        @endif
                    </td>
                    <td class="small-text" style="color: #64748b;">{{ $usage->notes }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>{{ __('StockProNex Inventory Management System') }}</strong> • {{ __('Professional Report') }} • &copy; {{ date('Y') }} {{ __('All Rights Reserved.') }}
    </div>
</body>
</html>
