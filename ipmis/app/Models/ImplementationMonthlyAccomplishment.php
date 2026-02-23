<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementationMonthlyAccomplishment extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function implementation () : BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
