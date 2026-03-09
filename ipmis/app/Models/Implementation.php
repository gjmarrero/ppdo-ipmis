<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Implementation extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];
    public function funded_project(): BelongsTo
    {
        return $this->belongsTo(FundedProject::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function implementation_monthly_accomplishments(): HasMany
    {
        return $this->hasMany(ImplementationMonthlyAccomplishment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImplementationDocumentStatus()
    {
        if ($this->date_received) {
            return 'Docs Received';
        }

        return 'No Docs Received';

    }

    public function orders(): HasMany
    {
        return $this->hasMany(ImplementationOrder::class);
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(ImplementationInspection::class);
    }

    // protected $casts = [
    //     'date_start' => 'date:Y-m-d',
    // ];
}
