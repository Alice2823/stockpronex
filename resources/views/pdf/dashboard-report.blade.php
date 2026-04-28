<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>StockProNex Analytics Report - {{ date('d M Y') }}</title>
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

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin: 30px 0 15px 0;
            border-left: 4px solid #0369a1;
            padding-left: 10px;
            text-transform: uppercase;
        }

        .card-container {
            margin-bottom: 30px;
        }
        .card-table {
            width: 100%;
        }
        .card-table td {
            width: 25%;
            padding: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
        }
        .card-label {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .card-value {
            font-size: 20px;
            color: #0c4a6e;
            font-weight: bold;
            margin-top: 5px;
        }

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
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 15px 40px;
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
                        {{ auth()->user()->address ?? 'Inventory Management System' }}
                    </div>
                </td>
                <td align="right" style="vertical-align: bottom;">
                    <div class="report-subtitle" style="font-weight: bold; color: #1e293b;">
                        {{ __('ANALYTICS REPORT') }}
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
        <div class="section-title">{{ __('Key Performance Indicators') }}</div>
        <div class="card-container">
            <table class="card-table">
                <tr>
                    <td>
                        <div class="card-label">{{ __('Total Stock Units') }}</div>
                        <div class="card-value">{{ number_format($cards['total_stock']) }}</div>
                    </td>
                    <td>
                        <div class="card-label">{{ __('Units Used (Period)') }}</div>
                        <div class="card-value">{{ number_format($cards['total_used']) }}</div>
                    </td>
                    <td>
                        <div class="card-label">{{ __('Units Added (Period)') }}</div>
                        <div class="card-value">{{ number_format($cards['total_added']) }}</div>
                    </td>
                    <td style="border-bottom: 3px solid #ef4444;">
                        <div class="card-label">{{ __('Low Stock Alerts') }}</div>
                        <div class="card-value" style="color: #ef4444;">{{ $cards['low_stock'] }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title">{{ __('Top Moving Products') }}</div>
        <table width="100%">
            <thead>
                <tr>
                    <th>{{ __('Rank') }}</th>
                    <th>{{ __('Product Name') }}</th>
                    <th style="text-align: right;">{{ __('Total Units Used') }}</th>
                    <th style="text-align: right;">{{ __('% of Usage') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalUsage = $top_products->sum('total');
                @endphp
                @foreach($top_products as $index => $product)
                <tr>
                    <td style="color: #64748b;">#{{ $index + 1 }}</td>
                    <td><strong>{{ $product['name'] }}</strong></td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($product['total']) }}</td>
                    <td style="text-align: right; color: #64748b;">
                        {{ $totalUsage > 0 ? round(($product['total'] / $totalUsage) * 100, 1) : 0 }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 50px; background-color: #f0f9ff; padding: 25px; border-radius: 8px; border: 1px solid #bae6fd;">
            <p style="margin: 0; color: #0369a1; font-weight: bold; font-size: 16px;">{{ __('Analytics Insights') }}</p>
            <p style="margin: 10px 0 0 0; color: #075985; font-size: 14px;">
                {{ __('This report summarizes the operational activities for the selected period.') }} 
                {{ __('Product usage is') }} {{ $cards['total_used'] > $cards['total_added'] ? __('higher') : __('lower') }} {{ __('than stock additions in the current timeframe.') }}
                {{ __('Regular audits are recommended for the') }} {{ $cards['low_stock'] }} {{ __('low stock items identified.') }}
            </p>
        </div>
    </div>

    <div class="footer">
        <strong>StockProNex {{ __('Performance Analytics') }}</strong> • {{ __('Executive Summary') }} • &copy; {{ date('Y') }}
    </div>
</body>
</html>
