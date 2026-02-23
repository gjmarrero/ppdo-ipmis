<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnvironmentalClearanceCmrRequest;
use App\Http\Resources\EnvironmentalClearanceCmrResource;
use App\Models\EnvironmentalClearanceCmr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentalClearanceCmrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EnvironmentalClearanceCmrRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('file')) {
            $data['file'] = $request
                ->file('file')
                ->store('environmental_clearance/cmr', 'public');
        }

        $cmr = EnvironmentalClearanceCmr::create($data);
        return new EnvironmentalClearanceCmrResource($cmr);

    }

    /**
     * Display the specified resource.
     */
    public function show(EnvironmentalClearanceCmr $environmentalClearanceCmr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnvironmentalClearanceCmr $environmentalClearanceCmr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EnvironmentalClearanceCmr $environmentalClearanceCmr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnvironmentalClearanceCmr $environmentalClearanceCmr)
    {
        //
    }
}
