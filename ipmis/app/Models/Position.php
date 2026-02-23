<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function odsu() : BelongsTo
    {
        return $this->belongsTo(Odsu::class);
    }

    public function plantilla() : HasMany
    {
        return $this->hasMany(Plantilla::class);
    }
}
