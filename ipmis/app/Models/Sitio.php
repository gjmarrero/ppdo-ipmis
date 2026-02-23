<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sitio extends Model
{
    public function barangay() : BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }
}
