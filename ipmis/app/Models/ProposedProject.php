<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposedProject extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = [];

    public function project () : BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function project_type () : BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
