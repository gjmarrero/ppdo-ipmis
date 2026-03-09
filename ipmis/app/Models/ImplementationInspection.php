<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImplementationInspection extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function implementation()
    {
        return $this->belongsTo(Implementation::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ImplementationInspectionDocument::class,'implementation_inspection_id', 'id');
    }   
}
