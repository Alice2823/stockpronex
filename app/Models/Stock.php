<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Notifications\LowStockNotification;

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'quantity',
        'mrp',
        'price',
        'description',
        'low_stock_alert_sent',
        'business_attributes'
    ];

    protected $casts = [
        'business_attributes' => 'array'
    ];

    /**
     * Smart Low Stock Email Alert System
     * Safe with OTP system
     * No spam protection included
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($stock) {

            // Run only when quantity is changed
            if ($stock->isDirty('quantity')) {

                /**
                 * LOW STOCK ALERT
                 */
                if ($stock->quantity <= 10 && !$stock->low_stock_alert_sent) {

                    $user = User::find($stock->user_id);

                    if ($user) {
                        try {
                            // Send email notification
                            $user->notify(new LowStockNotification($stock));
                        } catch (\Exception $e) {
                            // Log the error but don't crash the app
                            \Log::error('Failed to send low stock notification: ' . $e->getMessage());
                        }

                        // Mark alert as sent (prevent spam)
                        $stock->low_stock_alert_sent = true;
                    }
                }

                /**
                 * RESET ALERT WHEN RESTOCKED
                 */
                if ($stock->quantity > 10 && $stock->low_stock_alert_sent) {

                    $stock->low_stock_alert_sent = false;
                }
            }

        });
    }

    /**
     * Relationship: Stock belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barcodes()
    {
        return $this->hasMany(StockBarcode::class);
    }

    public function units()
    {
        return $this->hasMany(StockUnit::class);
    }

    public function usages()
    {
        return $this->hasMany(StockUsage::class);
    }
}