<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreengineeringSiteProblem extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function preengineering_status(): BelongsTo
    {
        return $this->belongsTo(PreengineeringStatus::class);
    }
}
