<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'meeting_date',
        'location',
        'agenda',
        'meeting_link'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
