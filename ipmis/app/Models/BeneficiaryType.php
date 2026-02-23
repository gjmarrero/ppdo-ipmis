<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeneficiaryType extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function projectTypes(): BelongsToMany
    {
        return $this->belongsToMany(ProjectType::class, 'project_beneficiary_pivots','beneficiary_type_id','project_type_id');
    }

    public function beneficiary(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }
}
