<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PreengineeringStatus extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function funded_project () : BelongsTo
    {
        return $this->belongsTo(FundedProject::class);
    }

    public function employee () : BelongsTo
    {
        return $this->belongsTo(Employee::class,'employee_id','id');
    }

    public function projectType (): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function latestActivity () : HasOne
    {
        return $this->hasOne(PreengineeringActivity::class,'preengineering_status_id','id')->latestOfMany();
    }

    public function preengineering_images () : HasMany
    {
        return $this->hasMany(PreengineeringImage::class, 'pre_engineering_id','id');
    }

    public function preengineering_documents () : HasMany
    {
        return $this->hasMany(PreengineeringDocument::class);
    }

    public function scopes () : HasMany
    {
        return $this->HasMany(PreengineeringScopeOfWork::class, 'preengineering_status_id','id');
    }

    public function preengineering_site_problems () : HasMany
    {
        return $this->hasMany(PreengineeringSiteProblem::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee_qcp() : BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id_qcp', 'id');
    }

    public function approved_pow () : HasOne
    {
        return $this->hasOne(ApprovedPow::class,'preengineering_status_id','id')->latestOfMany();
    }

    public function getQcpStatusAttribute()
    {
        if ($this->date_received_by_qc && $this->date_qcp_prepared === null) {
            return 'Received';
        }

        if ($this->date_received_by_qc && $this->date_qcp_prepared && $this->date_qcp_reviewed === null) {
            return 'QCP Prepared';
        }

        if ($this->date_qcp_reviewed) {
            return 'QCP Reviewed';
        }


        return 'Pending';

    }

    public function getReviewStatusAttribute()
    {
        if($this->date_received_by_ape && $this->date_reviewed && $this->date_recommended_for_approval === null){
            return 'On going review';
        }

        if($this->date_recommended_for_approval && $this->date_submitted_to_lce === null) {
            return 'Reviewed';
        }

        if($this->date_submitted_to_lce) {
            return 'Recommended for Approval';
        }

        return 'Pending';
    }
}
