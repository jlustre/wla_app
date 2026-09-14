<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCompanyAffiliation extends Model
{
    use HasFactory;

    protected $table = 'member_company_affiliations';

    protected $fillable = [
        'user_id',
        'company_id',
        'status',
        'joined_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
