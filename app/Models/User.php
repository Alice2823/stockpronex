<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'provider_id',
        'provider_name',
        'is_subscribed',
        'business_type',
        'business_name',
        'address',
        'language',
        'currency',
        'tax_number',
        'tax_percentage',
        'payment_id',
        'invoice_color',
        'bank_name',
        'account_number',
        'ifsc_code',
        'upi_id',
        'ca_sharing_enabled',
        'ca_name',
        'ca_whatsapp',
        'ca_email',
        'plan',
        'billing_cycle',
        'subscription_ends_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the currency symbol based on user's preference.
     */
    public function getCurrencySymbolAttribute()
    {
        $symbols = [
            'USD' => '$',
            'INR' => '₹',
            'GBP' => '£',
            'EUR' => '€',
        ];

        return $symbols[$this->currency] ?? '$';
    }

    public function barcodes()
    {
        return $this->hasMany(StockBarcode::class);
    }

    public function hasFeature($feature)
    {
        return \App\Constants\Plan::hasFeature($this->plan, $feature);
    }

    public function canAddStock()
    {
        $limit = \App\Constants\Plan::getLimit($this->plan);
        if ($limit === -1) {
            return true;
        }

        $count = \App\Models\Stock::where('user_id', $this->id)->count();
        return $count < $limit;
    }

    public function isPlan($plan)
    {
        return $this->plan === $plan;
    }

    /**
     * Get the tax percentage for the user's business type.
     * Prioritizes manually set tax_percentage if > 0.
     */
    public function getTaxPercentage()
    {
        if ($this->tax_percentage > 0) {
            return (float) $this->tax_percentage;
        }

        $taxRates = [
            'General Inventory' => 18,
            'Gold / Jewellery'  => 3,
            'Electronics'       => 18,
            'Grocery'           => 5,
            'Clothing'          => 12,
            'Medical Store'     => 12,
            'Pharmacy'          => 12,
            'Supermarket'       => 12,
            'Hardware'          => 18,
            'Mobile Shop'       => 18,
            'Automobile parts'  => 28,
            'Furniture'         => 18,
            'Cosmetic'          => 18,
            'Book Store'        => 12,
            'Restaurant'        => 5,
            'Agricultural'      => 0,
            'Wholesale'         => 18,
            'Other'             => 5,
        ];

        return $taxRates[$this->business_type] ?? 18; 
    }
}