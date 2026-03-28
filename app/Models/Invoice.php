<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'stock_id',
        'usage_id',
        'barcode',
        'invoice_number',
        'subtotal',
        'amount',
        'tax_amount',
        'tax_percentage',
        'total_amount',
        'customer_name',
        'company_name',
        'phone',
        'address',
        'payment_method',
        'status',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function usage()
    {
        return $this->belongsTo(StockUsage::class, 'usage_id');
    }

    public function stockUsage()
    {
        return $this->belongsTo(StockUsage::class, 'usage_id');
    }
}
