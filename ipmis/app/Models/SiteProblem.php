<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteProblem extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function funded_project() : BelongsTo
    {
        return $this->belongsTo(FundedProject::class);
    }

    public function fundsource() : BelongsTo
    {
        return $this->belongsTo(Fundsource::class);
    }

    public function archive() : BelongsTo
    {
        return $this->belongsTo(Archive::class);
    }

    public function site_problem_files () : HasMany
    {
        return $this->hasMany(SiteProblemFile::class);
    }
}
