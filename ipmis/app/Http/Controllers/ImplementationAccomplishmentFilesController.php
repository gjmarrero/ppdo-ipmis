<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ImplementationMonthlyAccomplishment;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class ImplementationAccomplishmentFilesController extends Controller
{
    use HasFactory, HasUuids;

    // public function implementation_monthly_accomplishment () : BelongsTo
    // {
    //     return $this->belongsTo(ImplementationMonthlyAccomplishment::class);
    // }

    // public function user () : BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}
