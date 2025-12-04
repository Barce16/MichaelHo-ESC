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
        'staff_id',
        'scheduled_date',
        'scheduled_time',
        'remarks',
        'contact_number',
        'venue',
        'proof_image',
        'proof_uploaded_at',
        'notified_at',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'completed_at' => 'datetime',
        'notified_at' => 'datetime',
        'proof_uploaded_at' => 'datetime',
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

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Check if staff has been notified
    public function isNotified(): bool
    {
        return !is_null($this->notified_at);
    }

    // Mark as notified
    public function markAsNotified(): void
    {
        $this->update(['notified_at' => now()]);
    }

    // Check if proof has been uploaded
    public function hasProof(): bool
    {
        return !is_null($this->proof_image);
    }

    // Upload proof
    public function uploadProof(string $path): void
    {
        $this->update([
            'proof_image' => $path,
            'proof_uploaded_at' => now(),
        ]);
    }

    // Get proof URL
    public function getProofUrlAttribute(): ?string
    {
        if ($this->proof_image) {
            return asset('storage/' . $this->proof_image);
        }
        return null;
    }

    // Derived Status Methods
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    public function isToday(): bool
    {
        return !$this->isCompleted() && $this->scheduled_date && $this->scheduled_date->isToday();
    }

    public function isUpcoming(): bool
    {
        return !$this->isCompleted() && $this->scheduled_date && $this->scheduled_date->isFuture();
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && $this->scheduled_date && $this->scheduled_date->isPast() && !$this->scheduled_date->isToday();
    }

    // Get derived status
    public function getStatusAttribute(): string
    {
        if (!$this->scheduled_date) {
            return 'not_set';
        }

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
            'not_set' => 'Not set',
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
            'not_set' => 'gray',
            default => 'gray',
        };
    }

    // Get formatted scheduled datetime
    public function getFormattedScheduleAttribute(): string
    {
        if (!$this->scheduled_date) {
            return 'Not scheduled';
        }

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

    public function scopeForStaff($query, $staffId)
    {
        return $query->where('staff_id', $staffId);
    }

    public function scopeNotified($query)
    {
        return $query->whereNotNull('notified_at');
    }

    public function scopeNotNotified($query)
    {
        return $query->whereNull('notified_at');
    }
}
