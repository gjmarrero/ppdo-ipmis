<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Validation extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function project () : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validation_supporting_documents () : HasMany
    {
        return $this->hasMany(ValidationSupportingDocument::class);
    }

    public function validation_responsible_people () : HasMany
    {
        return $this->hasMany(ValidationResponsiblePerson::class);
    }

    public function validation_images () : HasMany
    {
        return $this->hasMany(ValidationImage::class);
    }

    public function validation_map_images () : HasMany
    {
        return $this->hasMany(ValidationMapImage::class);
    }
    public function beneficiaries () : HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    public function validation_other_requirements () : HasMany
    {
        return $this->hasMany(ValidationOtherRequirement::class);
    }
}
