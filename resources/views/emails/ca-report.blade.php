@component('mail::message')
# Monthly Business Report

Hello CA {{ $caName }},

Please find the monthly business profit and transaction report for **{{ $businessName }}** for the month of {{ $month }}.

This report contains:
- Overall profit summary
- Detailed stock-wise profit breakdown
- Sales history for the past month

If you have any questions regarding these figures, please reach out to {{ $businessName }} directly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
