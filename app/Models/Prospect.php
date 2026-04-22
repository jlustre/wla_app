<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Timeline;

class Prospect extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'hotness',
        'last_action',
        'notes',
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
     * Get the most recent stage (action) from the timelines.
     */
    public function getLatestStageAttribute()
    {
        $latest = $this->timelines()->orderByDesc('action_at')->first();
        return $latest ? $latest->action : null;
    }
}
