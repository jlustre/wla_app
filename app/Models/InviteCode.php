<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InviteCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'status',
        'used_by_user_id',
        'used_at',
        'expires_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usedBy()
    {
        return $this->belongsTo(User::class, 'used_by_user_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isUsable(): bool
    {
        return $this->status === 'active' && ! $this->isExpired();
    }

    public function effectiveStatus(): string
    {
        return $this->status === 'active' && $this->isExpired() ? 'expired' : $this->status;
    }

    public function markUsed(User $newUser): void
    {
        $this->update([
            'status' => 'used',
            'used_by_user_id' => $newUser->id,
            'used_at' => now(),
        ]);
    }

    public static function currentFor(User $user): self
    {
        $existing = static::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        return $existing ?: static::generateFor($user);
    }

    public static function regenerateFor(User $user): self
    {
        static::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'revoked']);

        return static::generateFor($user);
    }

    protected static function generateFor(User $user): self
    {
        return static::create([
            'user_id' => $user->id,
            'code' => static::generateUniqueCode(),
            'status' => 'active',
            'expires_at' => now()->addDays(2),
        ]);
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random(10));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public static function findUsable(string $code): ?self
    {
        $invite = static::where('code', $code)->first();

        return $invite && $invite->isUsable() ? $invite : null;
    }
}
