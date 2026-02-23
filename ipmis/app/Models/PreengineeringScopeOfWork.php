<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreengineeringScopeOfWork extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function preengineering_status(): BelongsTo
    {
        return $this->belongsTo(PreengineeringStatus::class);
    }

    public function specific_scope_of_work(): BelongsTo
    {
        return $this->belongsTo(SpecificScopeOfWork::class,'scope_of_work_id', 'id');
    }
}
