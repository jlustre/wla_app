<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Timeline;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'hotness',
        'next_follow_up',
        'notes',
    ];

    protected $casts = [
        'next_follow_up' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all timelines for the prospect (one-to-many).
     */
    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    /**
     * The most recently LOGGED activity entry, used to place this prospect
     * in the funnel/kanban board. Ordered by id (insertion order), not
     * action_at: action_at is a user-editable, minute-precision date that
     * can be backdated or simply collide with another entry logged in the
     * same minute, so it can't reliably say which entry came last. Uses
     * latestOfMany() so eager-loading it across a whole list of prospects
     * costs one extra query, not N+1.
     */
    public function latestActivity()
    {
        return $this->hasOne(Timeline::class)->latestOfMany('id');
    }

    /**
     * Get the most recent stage (action) from the timelines.
     */
    public function getLatestStageAttribute()
    {
        $latest = $this->timelines()->orderByDesc('action_at')->first();
        return $latest ? $latest->action : null;
    }
}
