<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory,HasUuids;

    public function municipality() : BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }

    public function locations() : HasMany
    {
        return $this->hasMany(Location::class);
    }
}
