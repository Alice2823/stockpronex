<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stock Inventory Report - {{ date('d M Y') }}</title>
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

        .summary-container {
            margin-bottom: 30px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
        }
        .summary-table {
            width: 100%;
        }
        .summary-table td {
            width: 25%;
            text-align: center;
            border: none;
        }
        .summary-label {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
        }
        .summary-value {
            font-size: 18px;
            color: #1e293b;
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
        .low-stock {
            color: #ef4444;
            font-weight: bold;
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
                        STOCK IN (INVENTORY) REPORT
                    </div>
                    <div class="report-date">
                        Generated on: {{ now()->format('d M Y, h:i A') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <hr style="border: 1px solid #ddd; margin-top: 10px; margin-bottom: 20px;">

    <div class="container">
        <div class="summary-container">
            <table class="summary-table">
                <tr>
                    <td>
                        <div class="summary-label">Total Items</div>
                        <div class="summary-value">{{ $stocks->count() }}</div>
                    </td>
                    <td>
                        <div class="summary-label">Total Units</div>
                        <div class="summary-value">{{ number_format($stocks->sum('quantity')) }}</div>
                    </td>
                    <td>
                        <div class="summary-label">Total Valuation</div>
                        <div class="summary-value">₹{{ number_format($stocks->sum(function($s){ return $s->price * $s->quantity; }), 2) }}</div>
                    </td>
                    <td>
                        <div class="summary-label">Low Stock</div>
                        <div class="summary-value" style="color: #ef4444;">{{ $stocks->where('quantity', '<', 10)->count() }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <table width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>MRP</th>
                    <th>Selling Price</th>
                    <th>Total Value</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                <tr>
                    <td style="color: #64748b;">#{{ $stock->id }}</td>
                    <td><strong>{{ $stock->name }}</strong></td>
                    <td class="{{ $stock->quantity < 10 ? 'low-stock' : '' }}" style="font-weight: bold;">
                        {{ number_format($stock->quantity) }}
                    </td>
                    <td>₹{{ number_format($stock->mrp ?? 0, 2) }}</td>
                    <td>₹{{ number_format($stock->price, 2) }}</td>
                    <td style="font-weight: bold;">₹{{ number_format($stock->price * $stock->quantity, 2) }}</td>
                    <td>
                        @if($stock->quantity < 5)
                            <span style="color: #ef4444; font-weight: bold;">CRITICAL</span>
                        @elseif($stock->quantity < 10)
                            <span style="color: #f59e0b; font-weight: bold;">LOW</span>
                        @else
                            <span style="color: #10b981; font-weight: bold;">HEALTHY</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>StockProNex Inventory Management System</strong> • Professional Inventory Audit • &copy; {{ date('Y') }}
    </div>
</body>
</html>
