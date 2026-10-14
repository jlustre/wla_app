<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarShare extends Model
{
    protected $fillable = [
        'calendar_id',
        'shared_with_user_id',
        'permission',
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }

    public function sharedWithUser()
    {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    public function canEdit(): bool
    {
        return $this->permission === 'edit';
    }
}
