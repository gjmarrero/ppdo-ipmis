<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ValidationOtherRequirement extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function validation(): BelongsTo
    {
        return $this->belongsTo(Validation::class);
    }

    public function pamb_area(): BelongsTo{
        return $this->belongsTo(ECPambType::class, 'pamb_type_id', 'id');
    }

    public function files(): HasMany{
        return $this->hasMany(OtherRequirementsFile::class, 'other_requirement_id', 'id');
    }
}
