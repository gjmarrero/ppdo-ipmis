<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectOtherRequirement extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(OtherRequirementsFile::class, 'other_requirement_id', 'id');
    }

    public function pamb_area(): BelongsTo
    {
        return $this->belongsTo(ECPambType::class, 'pamb_type_id', 'id');
    }
}
