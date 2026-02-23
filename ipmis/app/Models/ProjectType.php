<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectType extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'id'=>'string'
    ];

    public function funded_projects () : HasMany
    {
        return $this->hasMany(FundedProject::class);
    }

    public function beneficiaryTypes () : BelongsToMany
    {
        return $this->belongsToMany(BeneficiaryType::class, 'project_beneficiary_pivots','project_type_id','beneficiary_type_id');
    }
}
