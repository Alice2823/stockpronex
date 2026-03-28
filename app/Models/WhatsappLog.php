<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $fillable = [
        'invoice_id',
        'user_id',
        'phone',
        'status',
        'response_json',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
