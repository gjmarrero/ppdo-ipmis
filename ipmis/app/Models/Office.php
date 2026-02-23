<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function employees () : HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function odsu () : HasMany
    {
        return $this->hasMany(Odsu::class);
    }
}
