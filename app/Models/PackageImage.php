<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageImage extends Model
{
    protected $fillable = ['package_id', 'path', 'alt', 'sort'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
