<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'nid',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function filedCases(): HasMany
    {
        return $this->hasMany(CourtCase::class, 'filed_by');
    }

    public function assignedCases(): HasMany
    {
        return $this->hasMany(CourtCase::class, 'assigned_judge_id');
    }

    // Role helpers
    public function isLawyer(): bool
    {
        return $this->hasRole('Lawyer');
    }

    public function isJudge(): bool
    {
        return $this->hasRole('Judge');
    }

    public function isLitigant(): bool
    {
        return $this->hasRole('Litigant');
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['Admin', 'Superadmin']);
    }
}
