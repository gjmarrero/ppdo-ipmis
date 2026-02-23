<?php

namespace App\Models;

use App\Project;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSupportingDocument extends Model
{
    use HasFactory,HasUuids;

    protected $guarded = [];

    public function project () : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
