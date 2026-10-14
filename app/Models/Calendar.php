<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'color',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isHoliday(): bool
    {
        return $this->type === 'holiday';
    }

    public function scopeHolidays($query)
    {
        return $query->where('type', 'holiday');
    }

    public function scopePersonal($query)
    {
        return $query->where('type', 'personal');
    }

    public function events()
    {
        return $this->hasMany(CalendarEvent::class);
    }

    public function shares()
    {
        return $this->hasMany(CalendarShare::class);
    }

    /**
     * Members this calendar has been shared with.
     */
    public function sharedWith()
    {
        return $this->belongsToMany(User::class, 'calendar_shares', 'calendar_id', 'shared_with_user_id')
            ->withPivot('permission')
            ->withTimestamps();
    }

    public function colorHex(): string
    {
        return CalendarEvent::colorHex($this->color);
    }

    /**
     * The member's default personal calendar, creating one (or promoting
     * their oldest personal calendar) if they somehow don't have one yet.
     * Shared by App\Livewire\Member\CalendarManager and
     * App\Services\OnboardingService so there's one source of truth.
     */
    public static function ensureDefaultFor(User $user): self
    {
        $default = static::where('user_id', $user->id)->where('is_default', true)->first();

        if ($default) {
            return $default;
        }

        $fallback = static::personal()->where('user_id', $user->id)->first();

        if ($fallback) {
            $fallback->update(['is_default' => true]);

            return $fallback;
        }

        return static::create([
            'user_id' => $user->id,
            'type' => 'personal',
            'name' => 'My Calendar',
            'color' => 'peacock',
            'is_default' => true,
        ]);
    }
}
