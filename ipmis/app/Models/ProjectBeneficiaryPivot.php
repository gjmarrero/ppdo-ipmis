<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBeneficiaryPivot extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function project_type():BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function beneficiary_type():BelongsTo
    {
        return $this->belongsTo(BeneficiaryType::class);
    }

    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }
}
