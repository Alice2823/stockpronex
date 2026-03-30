<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit Management Report</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 10px; 
            color: #334155; 
            background-color: #ffffff; 
            margin: 40px;
            line-height: 1.6;
        }
        
        /* Header */
        .header { 
            display: block;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header-left { float: left; width: 60%; }
        .header-right { float: right; width: 38%; text-align: right; }
        .clearfix { clear: both; }

        .business-name { 
            color: #0f172a; 
            font-size: 22px; 
            font-weight: 900; 
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .tagline { color: #64748b; font-size: 11px; margin: 5px 0 0 0; }
        .report-title { color: #1e293b; font-size: 12px; font-weight: 800; text-transform: uppercase; margin: 0; }
        .gen-date { color: #94a3b8; font-size: 9px; margin-top: 5px; }
        
        /* Section Titles */
        .section-title {
            color: #1e293b;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 25px 0 15px 0;
            padding-left: 10px;
            border-left: 4px solid #0369a1;
            letter-spacing: 0.5px;
        }

        /* KPI Cards */
        .summary-container { 
            display: table;
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            border-collapse: collapse;
            overflow: hidden;
            margin-bottom: 30px;
        }
        .summary-item { 
            display: table-cell;
            width: 33.33%;
            padding: 15px 10px;
            text-align: center;
            border-right: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .summary-item:last-child { border-right: none; }
        
        .summary-label { 
            display: block; 
            font-weight: 700; 
            color: #64748b; 
            text-transform: uppercase; 
            font-size: 8px; 
            margin-bottom: 8px; 
            letter-spacing: 0.5px;
        }
        .summary-value { 
            display: block; 
            font-size: 16px; 
            font-weight: 900; 
            color: #0f172a; 
        }
        .value-net { color: #0369a1; }
        .value-discount { color: #ef4444; }

        /* Table */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th { 
            background-color: #f1f5f9; 
            color: #475569; 
            padding: 10px 12px; 
            text-align: left; 
            text-transform: uppercase; 
            font-size: 9px;
            font-weight: 800;
            border-bottom: 1px solid #cbd5e1;
        }
        td { 
            padding: 10px 12px; 
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 10px;
        }
        .product-name-cell { color: #0f172a; font-weight: 700; font-size: 10px; }
        .bold-navy { color: #0f172a; font-weight: 700; }
        
        /* Insights Box */
        .insights-box {
            margin-top: 40px;
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 15px;
        }
        .insights-title {
            color: #0369a1;
            font-size: 12px;
            font-weight: 800;
            margin: 0 0 8px 0;
        }
        .insights-text {
            color: #0c4a6e;
            font-size: 9px;
            margin: 0;
            line-height: 1.5;
        }

        .footer { 
            position: fixed; 
            bottom: 30px; 
            left: 40px; 
            right: 40px;
            text-align: center; 
            font-size: 8px; 
            color: #94a3b8; 
            border-top: 1px solid #f1f5f9; 
            padding-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1 class="business-name">STOCKPRONEX</h1>
            <p class="tagline">Comprehensive Inventory & Profit Analytics Report</p>
        </div>
        <div class="header-right">
            <p class="report-title">Analytics Report</p>
            <p class="gen-date">Generated on: {{ $generationDate }}</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">Key Profit Indicators</div>
    <div class="summary-container">
        <div class="summary-item">
            <span class="summary-label">Total Units Sold</span>
            <span class="summary-value">{{ number_format($stocks->sum('units_sold')) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Discounts Given</span>
            <span class="summary-value value-discount">₹{{ number_format($totalOverallDiscount, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Net Profit Earned</span>
            <span class="summary-value value-net">₹{{ number_format($totalOverallProfit, 2) }}</span>
        </div>
    </div>

    <div class="section-title">Stock Performance Analytics</div>
    <table>
        <thead>
            <tr>
                <th width="30%">Product Name</th>
                <th>MRP (Cost)</th>
                <th>Sale Price</th>
                <th style="text-align: center;">Sold</th>
                <th>Gross profit</th>
                <th>Discount</th>
                <th>Net Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $stock)
                <tr>
                    <td class="product-name-cell">{{ $stock->name }}</td>
                    <td>₹{{ number_format($stock->mrp ?? 0, 2) }}</td>
                    <td>₹{{ number_format($stock->price, 2) }}</td>
                    <td style="text-align: center; font-weight: 700;">{{ $stock->units_sold }}</td>
                    <td class="bold-navy">₹{{ number_format($stock->gross_profit, 2) }}</td>
                    <td style="color: #ef4444;">₹{{ number_format($stock->total_discount, 2) }}</td>
                    <td class="bold-navy" style="color: {{ $stock->calculated_profit >= 0 ? '#0369a1' : '#ef4444' }};">
                        {{ $stock->calculated_profit >= 0 ? '+' : '-' }}₹{{ number_format(abs($stock->calculated_profit), 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="insights-box">
        <h4 class="insights-title">Profit Analytics Insights</h4>
        <p class="insights-text">
            This financial report summarizes your realized profits after accounting for item-level discounts. 
            All net profit values indicate the actual revenue retained after subtracting the purchase cost (MRP) 
            and any percentage-based or flat discounts applied during sales. Regular auditing is recommended 
            for items showing concentrated high discounts.
        </p>
    </div>

    <div class="footer">
        StockProNex | Automated Profit Management System | Page 1 of 1
    </div>
</body>
</html>
