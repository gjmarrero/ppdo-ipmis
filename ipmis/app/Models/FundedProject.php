<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FundedProject extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function project () : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function fundsource () : BelongsTo
    {
        return $this->belongsTo(Fundsource::class);
    }

    public function funded_project_supporting_documents() : HasMany
    {
        return $this->hasMany(FundedProjectSupportingDocument::class);
    }

    public function preengineering_statuses () : HasMany
    {
        return $this->hasMany(PreengineeringStatus::class);
    }

    public function latestPreengineering()
    {
        return $this->hasOne(PreengineeringStatus::class)->latestOfMany();
    }

    public function environmental_clearances () : HasMany
    {
        return $this->hasMany(EnvironmentalClearance::class);
    }

    public function latestEnvironmentalClearance()
    {
        return $this->hasOne(EnvironmentalClearance::class)->latestOfMany();
    }

    public function implementations () : HasMany
    {
        return $this->hasMany(Implementation::class);
    }

    public function latestImplementation ()
    {
        return $this->hasOne(Implementation::class)->latestOfMany();
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplemental_budgets() : HasMany
    {
        return $this->hasMany(SupplementalBudget::class);
    }    

    public function site_problems() : HasMany
    {
        return $this->hasMany(SiteProblem::class);
    }

    public function latestSiteProblem()
    {
        return $this->hasOne(SiteProblem::class)->latestOfMany();
    }

}
