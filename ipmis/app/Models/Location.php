<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory, HasUuids;
    protected $guarded = [];

    public function project () : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function barangay () : BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function municipality () : BelongsTo
    {
        return $this->belongsTo(Municipality::class);
    }
}
