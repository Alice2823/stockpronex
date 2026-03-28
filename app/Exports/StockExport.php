<?php

namespace App\Exports;

use App\Models\Stock;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Stock::where('user_id', Auth::id())->get();
    }

    public function headings(): array
    {
        $user = Auth::user();
        return [
            ['Business Name:', $user->business_name ?? 'StockProNex'],
            ['Address:', $user->address ?? 'N/A'],
            ['Report:', 'Inventory Status Report'],
            ['Date:', date('Y-m-d H:i:s')],
            [''], // Empty row
            [
                'ID',
                'Name',
                'Quantity',
                'MRP',
                'Selling Price',
                'Total Value',
                'Description',
                'Created At',
            ]
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->id,
            $stock->name,
            $stock->quantity,
            $stock->mrp ?? '0.00',
            $stock->price,
            $stock->price * $stock->quantity,
            $stock->description,
            $stock->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
