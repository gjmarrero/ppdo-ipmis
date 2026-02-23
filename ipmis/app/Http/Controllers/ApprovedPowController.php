<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApprovedPow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedPowController extends Controller
{
    public function store (Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();

        ApprovedPow::create($data);

    }
}
