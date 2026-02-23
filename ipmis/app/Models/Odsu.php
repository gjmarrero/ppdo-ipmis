<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odsu extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function section() : BelongsTo
    {
        return $this->belongsTo(Section::class);    
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function getOfficeDivisionAttribute()
    {
        return trim(($this->office?->office ?? '') . ' - ' . ($this->division?->division ?? ''));
    }
}
