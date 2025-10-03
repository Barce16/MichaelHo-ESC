<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'price',
        'is_active',
        'event_styling',
        'coordination',
        'coordination_price',
        'event_styling_price',

    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function inclusions()
    {
        return $this->belongsToMany(Inclusion::class, 'package_inclusion')
            ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort');
    }

    protected $casts = [
        'is_active'     => 'boolean',
        'event_styling' => 'array',
        'coordination_price' => 'decimal:2',
        'event_styling_price' => 'decimal:2',
    ];
}
