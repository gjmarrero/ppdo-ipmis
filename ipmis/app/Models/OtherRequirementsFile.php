<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherRequirementsFile extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function otherRequirement(): BelongsTo {
        return $this->belongsTo(ProjectOtherRequirement::class, 'other_requirement_id', 'id');
    }
}
