<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProfitExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['stocks'];
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Cost (MRP)',
            'Selling Price',
            'Units Sold',
            'Gross Profit (Total)',
            'Total Discount Given',
            'Net Profit (Final)',
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->name,
            '₹' . formatIndianNumber($stock->mrp ?? 0, 2),
            '₹' . formatIndianNumber($stock->price, 2),
            $stock->units_sold,
            '₹' . formatIndianNumber($stock->gross_profit, 2),
            '₹' . formatIndianNumber($stock->total_discount, 2),
            '₹' . formatIndianNumber($stock->calculated_profit, 2),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
