<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourtCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'filing_date' => 'date',
        'next_hearing_date' => 'date',
    ];

    public function caseStatus(): BelongsTo
    {
        return $this->belongsTo(CaseStatus::class);
    }

    public function filedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'filed_by');
    }

    public function assignedJudge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_judge_id');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(CaseParty::class);
    }
}
