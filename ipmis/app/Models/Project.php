<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\BelongsToManyRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fundsource(): BelongsTo
    {
        return $this->belongsTo(Fundsource::class);
    }

    public function project_type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }
    public function validations(): HasMany
    {
        return $this->hasMany(Validation::class);
    }

    public function latestValidation(): HasOne
    {
        return $this->hasOne(Validation::class)->latestOfMany();
    }


    public function funded_projects(): HasMany
    {
        return $this->hasMany(FundedProject::class);
    }

    public function latestFunding(): HasOne
    {
        return $this->hasOne(FundedProject::class)->latestOfMany();
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }


    public function documents(): HasMany
    {
        return $this->hasMany(ProjectSupportingDocument::class);
    }

    public function otherRequirements(): HasMany
    {
        return $this->hasMany(ProjectOtherRequirement::class, 'project_id', 'id');
    }



    public function boards(): HasMany
    {
        return $this->hasMany(Board::class);
    }
}
