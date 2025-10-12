<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'customer_id',
        'package_id',
        'name',
        'event_date',
        'venue',
        'theme',
        'budget',
        'guests',
        'status',
        'notes'
    ];

    protected $casts = [
        'event_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'event_staff')
            ->withPivot(['assignment_role', 'pay_rate', 'pay_status'])
            ->withTimestamps();
    }

    public function inclusions()
    {
        return $this->belongsToMany(Inclusion::class, 'event_inclusion')
            ->withPivot('price_snapshot')
            ->withTimestamps();
    }

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Billing::class);
    }

    public function isDownpaymentPending()
    {
        return $this->payments()->where('payments.status', 'pending')
            ->whereHas('billing', function ($query) {
                $query->where('downpayment_amount', '>', 0);
            })
            ->exists();
    }

    public function meeting()
    {
        return $this->hasOne(Meeting::class);
    }

    public static function isDateAvailable(string $date, ?int $excludeEventId = null): bool
    {
        $maxEvents = config('events.max_events_per_day', 2);

        $query = static::whereDate('event_date', $date)
            ->whereNotIn('status', ['cancelled', 'canceled']);

        if ($excludeEventId) {
            $query->where('id', '!=', $excludeEventId);
        }

        return $query->count() < $maxEvents;
    }

    public static function getRemainingSlots(string $date, ?int $excludeEventId = null): int
    {
        $maxEvents = config('events.max_events_per_day', 2);

        $query = static::whereDate('event_date', $date)
            ->whereNotIn('status', ['cancelled', 'canceled']);

        if ($excludeEventId) {
            $query->where('id', '!=', $excludeEventId);
        }

        $bookedCount = $query->count();

        return max(0, $maxEvents - $bookedCount);
    }
}
