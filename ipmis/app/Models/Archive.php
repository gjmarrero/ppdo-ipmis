<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Archive extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function site_problems() : HasMany
    {
        return $this->hasMany(SiteProblem::class);
    }
}
