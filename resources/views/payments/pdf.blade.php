<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Payment History') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right {
            text-align: right;
        }
        .header {
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #111827;
        }
        .header p {
            margin: 0;
            color: #6b7280;
        }
        .badge-online {
            color: #15803d;
            font-weight: bold;
        }
        .badge-cash {
            color: #4b5563;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ __('Payment History Report') }}</h2>
        <p>{{ __('Generated on') }} {{ now('Asia/Kolkata')->format('M d, Y h:i A') }}</p>
        @if($startDate || $endDate)
            <p>
                {{ __('Filter:') }} 
                @if($startDate) {{ __('From') }} {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} @endif
                @if($endDate) {{ __('To') }} {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }} @endif
            </p>
        @endif
    </div>

    <div style="margin-bottom: 20px;">
        <table style="width: 50%; border: 1px solid #e5e7eb; margin-top: 0;">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">{{ __('Payment Summary') }}</th>
                </tr>
                <tr>
                    <th style="background-color: #ffffff;">{{ __('Method') }}</th>
                    <th style="text-align: center; background-color: #ffffff;">{{ __('Transactions') }}</th>
                    <th class="text-right" style="background-color: #ffffff;">{{ __('Total Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="badge-online" style="border-right: 1px solid #e5e7eb;">{{ __('ONLINE') }}</td>
                    <td style="text-align: center; border-right: 1px solid #e5e7eb;">{{ $summary['onlineCount'] }}</td>
                    <td class="text-right">{{ number_format($summary['onlineTotal'], 2) }}</td>
                </tr>
                <tr>
                    <td class="badge-cash" style="border-right: 1px solid #e5e7eb;">{{ __('CASH') }}</td>
                    <td style="text-align: center; border-right: 1px solid #e5e7eb;">{{ $summary['cashCount'] }}</td>
                    <td class="text-right">{{ number_format($summary['cashTotal'], 2) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; border-right: 1px solid #e5e7eb;">{{ __('TOTAL') }}</td>
                    <td style="text-align: center; font-weight: bold; border-right: 1px solid #e5e7eb;">{{ $summary['onlineCount'] + $summary['cashCount'] }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($summary['onlineTotal'] + $summary['cashTotal'], 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Time') }}</th>
                <th>{{ __('Invoice #') }}</th>
                <th>{{ __('Product Name') }}</th>
                <th>{{ __('Customer') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Payment Method') }}</th>
                <th class="text-right">{{ __('Amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse ($invoices as $invoice)
                @php $totalAmount += $invoice->amount; @endphp
                <tr>
                    <td>{{ $invoice->created_at->setTimezone('Asia/Kolkata')->format('M d, Y') }}</td>
                    <td>{{ $invoice->created_at->setTimezone('Asia/Kolkata')->format('h:i A') }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->stock->name ?? __('Unknown Product') }}</td>
                    <td>{{ $invoice->customer_name }}</td>
                    <td>{{ $invoice->phone }}</td>
                    <td>
                        @if($invoice->payment_method === 'online')
                            <span class="badge-online">{{ __('ONLINE') }}</span>
                        @else
                            <span class="badge-cash">{{ __('CASH') }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($invoice->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">{{ __('No payments found for the selected period.') }}</td>
                </tr>
            @endforelse
        </tbody>
        @if(count($invoices) > 0)
        <tfoot>
            <tr>
                <td colspan="7" class="text-right" style="font-weight: bold; padding-top: 15px;">{{ __('Total Amount:') }}</td>
                <td class="text-right" style="font-weight: bold; padding-top: 15px;">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

</body>
</html>
