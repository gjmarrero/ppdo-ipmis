<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ImplementationQtyControl extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function implementation()
    {
        return $this->belongsTo(Implementation::class);
    }
}
