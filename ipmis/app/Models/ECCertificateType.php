<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ECCertificateType extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];
    
    public function environmental_clearance () : HasMany{
        return $this->hasMany(EnvironmentalClearance::class);
    }

}
