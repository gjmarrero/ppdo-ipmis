<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreEngineeringActivityCreateRequest;
use App\Http\Resources\PreengineeringActivityResource;
use App\Models\PreengineeringActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreengineeringActivityController extends Controller
{
    public function store (PreEngineeringActivityCreateRequest $request) {
        $data = $request->validated();

        $preengineering_activity = PreengineeringActivity::create([
            ...$data,
            'user_id' => Auth::id()
        ]);

        return new PreengineeringActivityResource($preengineering_activity);
    }

    public function fetch_preengineering_activities($preengineering_id){
        $activities = PreengineeringActivity::with('employee')->where('preengineering_status_id', $preengineering_id)->get();

        return $activities;
    }
}
