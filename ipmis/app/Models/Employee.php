<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function validation_responsible_people () : HasMany
    {
        return $this->hasMany(ValidationResponsiblePerson::class);
    }

    public function preengineering_statuses () : HasMany
    {
        return $this->hasMany(PreengineeringStatus::class);
    }

    public function office () : BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    public function users () : HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getEmployeeFullNameAttribute()
    {
        return trim($this->last_name . ', ' . $this->first_name . ' ' . ($this->middle_name ?? ''));
    }

    public function plantilla () : BelongsTo
    {
        return $this->belongsTo(Plantilla::class);
    }

}
