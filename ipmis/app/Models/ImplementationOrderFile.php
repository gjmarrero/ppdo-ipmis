<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ImplementationOrderFile extends Model
{
    use HasUuids;

    protected $guarded = [];
    public function implementationOrder()
    {
        return $this->belongsTo(ImplementationOrder::class);
    }
}
