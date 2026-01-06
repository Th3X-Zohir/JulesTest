<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function courtCase(): BelongsTo
    {
        return $this->belongsTo(CourtCase::class);
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }
}
