<?php

namespace App\Models;

use App\Enums\InclusionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inclusion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'image',
        'price',
        'is_active',
        'contact_person',
        'contact_email',
        'contact_phone',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'category' => InclusionCategory::class,
    ];

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_inclusion')
            ->withPivot('notes')
            ->withTimestamps();
    }
    public function events()
    {
        return $this->belongsToMany(Event::class)
            ->withPivot(['price'])
            ->withTimestamps();
    }


    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&size=400&background=8B5CF6&color=FFFFFF";
    }
}
