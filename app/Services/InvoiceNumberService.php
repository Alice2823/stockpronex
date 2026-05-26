<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Cache;

class InvoiceNumberService
{
    public function create(callable $callback)
    {
        $year = date('Y');

        return Cache::lock("invoice-number:{$year}", 10)->block(5, function () use ($callback, $year) {
            return $callback($this->nextForYear($year));
        });
    }

    public function next(): string
    {
        $year = date('Y');

        return Cache::lock("invoice-number:{$year}", 10)->block(5, function () use ($year) {
            return $this->nextForYear($year);
        });
    }

    private function nextForYear(string $year): string
    {
        $lastInvoice = Invoice::where('invoice_number', 'like', "INV-{$year}-%")
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_number);
            $number = intval(end($parts)) + 1;
        }

        return "INV-{$year}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
