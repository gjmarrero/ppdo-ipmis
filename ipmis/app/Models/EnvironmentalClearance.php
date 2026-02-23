<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnvironmentalClearance extends Model
{
    use HasUuids, HasFactory;
    protected $guarded = [];

    public function funded_project(): BelongsTo
    {
        return $this->belongsTo(FundedProject::class);
    }

    public function certificate_type(): BelongsTo
    {
        return $this->belongsTo(ECCertificateType::class, 'e_c_certificate_type_id', 'id');
    }

    public function pamb_type(): BelongsTo
    {
        return $this->belongsTo(ECPambType::class, 'e_c_pamb_type_id', 'id');
    }

    public function employee_in_charge(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function environmental_clearance_files(): HasMany
    {
        return $this->hasMany(EnvironmentalClearanceFiles::class);
    }

    public function environmental_clearance_cmrs(): HasMany
    {
        return $this->hasMany(EnvironmentalClearanceCmr::class, 'environmental_clearance_id', 'id');
    }

    public function getEnvironmentalClearanceStatusAttribute()
    {
        if ($this->date_issued) {
            return 'Clearance Issued';
        }

        if ($this->date_application && ($this->date_issued === null)) {
            return 'On Process';
        }


        return 'No Clearance';

    }

}
