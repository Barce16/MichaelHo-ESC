<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'billing_id',
        'amount',
        'payment_method',
        'payment_image',
        'payment_date',
        'status'
    ];

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getPaymentImageUrlAttribute()
    {
        return asset('storage/' . $this->payment_image);
    }
}
