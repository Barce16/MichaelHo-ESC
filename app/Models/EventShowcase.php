<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventShowcase extends Model
{
    protected $fillable = [
        'type',
        'event_name',
        'description',
        'location',
        'image_path',
        'is_published',
        'display_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'display_order' => 'integer',
    ];

    public function getImageUrlAttribute(): string
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        return Storage::url($this->image_path);
    }

    public function publish(): void
    {
        $this->update(['is_published' => true]);
    }

    public function unpublish(): void
    {
        $this->update(['is_published' => false]);
    }
}
