<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FundsourceCreateRequest;
use App\Http\Resources\FundsourceResource;
use App\Models\Fundsource;
use Illuminate\Http\Request;

class FundsourceController extends Controller
{
    public function index(Request $request){
        $fundsources = Fundsource::all();
        return FundsourceResource::collection($fundsources);
    }

    public function store(FundsourceCreateRequest $request){
        $data = $request->validated();

        $fundsource = Fundsource::create($data);
        // $fundsource = $request->create($data);

        return new FundsourceResource($fundsource);
    }

    public function show(Fundsource $fundsource){
        $fundsource->load('projects');

        return new FundsourceResource($fundsource);
    }

    public function update(Fundsource $fundsource, FundsourceCreateRequest $request){
        $data = $request->validated();
        
        $fundsource->update($data);

        return new FundsourceResource($fundsource);
    }

    public function destroy(Fundsource $fundsource, Request $request){
        $fundsource->delete();

        return response()->json([
            'message' => 'Fundsource deleted successfully'
        ]);
    }
}
