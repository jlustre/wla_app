<?php

namespace App\Models;

use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\Invitation;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\MemberCompanyAffiliation;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => \App\Enums\UserStatus::class,
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {
            $user->profile()->create();
        });
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    public function directMembers()
    {
        return $this->hasMany(User::class, 'sponsor_id')
            ->whereColumn($this->qualifyColumn('id'), '!=', $this->qualifyColumn('sponsor_id'));
    }

    public function downlineCount(): int
    {
        $ids = [$this->id];
        $visited = [$this->id => true];
        $total = 0;

        while ($ids !== []) {
            $children = static::query()
                ->whereIn('sponsor_id', $ids)
                ->whereColumn('id', '!=', 'sponsor_id')
                ->pluck('id')
                ->all();

            $ids = [];

            foreach ($children as $childId) {
                if (isset($visited[$childId])) {
                    continue;
                }

                $visited[$childId] = true;
                $ids[] = $childId;
                $total++;
            }
        }

        return $total;
    }

    public function companyAffiliations()
    {
        return $this->hasMany(MemberCompanyAffiliation::class, 'user_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'inviter_id');
    }

    public function sponsorRelationships()
    {
        return $this->hasMany(SponsorRelationship::class, 'member_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }
}
