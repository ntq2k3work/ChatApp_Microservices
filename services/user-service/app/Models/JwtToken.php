<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    protected $fillable = [
        'user_id',
        'jti',
        'device_name',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expired_at', '>', now());
    }

    public function scopeLogoutWithoutThisDevice($query,$currentJti)
    {
        return $query->where('user_id', auth()->id())
        ->where('jti', '!=', $currentJti)
        ->delete();
    }

    public function scopeLogoutAllDevice($query,$userId)
    {
        return $query->where('user_id', $userId)
        ->delete();
    }
}
