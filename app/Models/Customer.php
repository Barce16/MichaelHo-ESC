<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_name', 'email', 'phone', 'address', 'user_id', 'gender'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasPendingPayments(): bool
    {
        return $this->events()
            ->whereHas('billing.payments', function ($query) {
                $query->where('status', Payment::STATUS_PENDING);
            })
            ->exists();
    }

    public function hasOutstandingBalance(): bool
    {
        return $this->events()
            ->whereHas('billing', function ($query) {
                $query->whereRaw('total_amount > (
                SELECT COALESCE(SUM(amount), 0) 
                FROM payments 
                WHERE billing_id = billings.id 
                AND status = ?
            )', [Payment::STATUS_APPROVED]);
            })
            ->exists();
    }
}
