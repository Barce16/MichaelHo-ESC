<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventSchedule extends Model
{
    protected $fillable = [
        'event_id',
        'inclusion_id',
        'scheduled_date',
        'scheduled_time',
        'remarks',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function inclusion(): BelongsTo
    {
        return $this->belongsTo(Inclusion::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Derived Status Methods
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    public function isToday(): bool
    {
        return !$this->isCompleted() && $this->scheduled_date->isToday();
    }

    public function isUpcoming(): bool
    {
        return !$this->isCompleted() && $this->scheduled_date->isFuture();
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && $this->scheduled_date->isPast() && !$this->scheduled_date->isToday();
    }

    // Get derived status
    public function getStatusAttribute(): string
    {
        if ($this->isCompleted()) {
            return 'completed';
        }

        if ($this->scheduled_date->isToday()) {
            return 'today';
        }

        if ($this->scheduled_date->isPast()) {
            return 'overdue';
        }

        return 'upcoming';
    }

    // Get status label for display
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'Completed',
            'today' => 'Today',
            'overdue' => 'Overdue',
            'upcoming' => 'Upcoming',
            default => 'Pending',
        };
    }

    // Get status color for badges
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed' => 'emerald',
            'today' => 'blue',
            'overdue' => 'rose',
            'upcoming' => 'amber',
            default => 'gray',
        };
    }

    // Get formatted scheduled datetime

    public function getFormattedScheduleAttribute(): string
    {
        $date = $this->scheduled_date->format('M d, Y');

        if ($this->scheduled_time) {
            $time = Carbon::parse($this->scheduled_time)->format('g:i A');
            return "{$date} at {$time}";
        }

        return $date;
    }

    // Mark as completed
    public function markAsCompleted(): void
    {
        $this->update(['completed_at' => now()]);
    }

    // Mark as incomplete (undo completion)
    public function markAsIncomplete(): void
    {
        $this->update(['completed_at' => null]);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeIncomplete($query)
    {
        return $query->whereNull('completed_at');
    }

    public function scopeForToday($query)
    {
        return $query->whereNull('completed_at')
            ->whereDate('scheduled_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereNull('completed_at')
            ->where('scheduled_date', '>', today())
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time');
    }

    public function scopeOverdue($query)
    {
        return $query->whereNull('completed_at')
            ->where('scheduled_date', '<', today());
    }
}
