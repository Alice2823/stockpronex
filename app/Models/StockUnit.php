<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'user_id',
        'barcode',
        'imei_number',
        'serial_number',
        'batch_number',
        'expiry_date',
        'additional_attributes',
        'status',
    ];

    protected $casts = [
        'additional_attributes' => 'array',
        'expiry_date' => 'date',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
