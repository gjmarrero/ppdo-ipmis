<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidationMapImage extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function validation() : BelongsTo
    {
        return $this->belongsTo(Validation::class);
    }
}
