<?php

namespace App\Exports;

use App\Models\StockUsage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockUsageExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return StockUsage::where('user_id', Auth::id())->with('stock')->latest()->get();
    }

    public function headings(): array
    {
        $user = Auth::user();
        return [
            ['Business Name:', $user->business_name ?? 'StockProNex'],
            ['Address:', $user->address ?? 'N/A'],
            ['Report:', 'Stock Usage History'],
            ['Date:', date('Y-m-d H:i:s')],
            [''], // Empty row
            [
                'Date',
                'Stock Name',
                'Quantity Used',
                'Notes',
            ]
        ];
    }

    public function map($usage): array
    {
        return [
            $usage->created_at->format('Y-m-d H:i:s'),
            $usage->stock->name,
            $usage->quantity,
            $usage->notes,
        ];
    }
}
