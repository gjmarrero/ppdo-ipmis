<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Beneficiary extends Model
{
    use HasFactory,HasUuids;

    protected $guarded = [];

    public function validation () : BelongsTo
    {
        return $this->belongsTo(Validation::class);
    }

    public function project_beneficiary_pivot() : BelongsTo
    {
        return $this->belongsTo(ProjectBeneficiaryPivot::class);
    }

    public function beneficiary_sdds () : HasMany
    {
        return $this->hasMany(BeneficiarySdd::class);
    }

    public function beneficiary_type () : BelongsTo
    {
        return $this->belongsTo(BeneficiaryType::class);
    }
}
