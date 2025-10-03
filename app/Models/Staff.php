<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;
    protected $table = 'staffs';
    protected $fillable = ['user_id', 'contact_number', 'role_type', 'address', 'gender', 'remarks', 'is_active', 'rate', 'rate_type',];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getNameAttribute()
    {
        return $this->user?->name;
    }
    public function getAvatarUrlAttribute()
    {
        return $this->user?->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name ?? 'Unknown') . '&background=E5E7EB&color=111827';
    }
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_staff', 'staff_id', 'event_id')->withPivot(['assignment_role', 'pay_rate', 'pay_status'])->withTimestamps();
    }
}
