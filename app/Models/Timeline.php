<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = [
        'prospect_id',
        'action',
        'notes',
        'action_at',
        'next_follow_up_dt',
    ];

    protected $casts = [
        'action_at' => 'datetime',
        'next_follow_up_dt' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
