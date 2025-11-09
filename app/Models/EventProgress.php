<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgress extends Model
{
    use HasFactory;

    protected $table = 'event_progress';

    protected $fillable = ['event_id', 'status', 'details', 'progress_date'];

    protected $casts = ['progress_date' => 'datetime'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
