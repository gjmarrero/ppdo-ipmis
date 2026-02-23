<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfficeCreateRequest;
use App\Http\Resources\OfficeResource;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index(Request $request){
        $offices = Office::all();
        return OfficeResource::collection($offices);
    }

    public function store(OfficeCreateRequest $request){
        $data = $request->validated();
        $office = Office::create($data);
        return new OfficeResource($office);
    }

    public function update(Office $office, OfficeCreateRequest $request){
        $data = $request->validated();
        
        $office->update($data);

        return new OfficeResource($office);
    }

    public function destroy(Office $office, Request $request){
        $office->delete();

        return response() -> json([
            'message' => 'Office successfully deleted'
        ]);
    }
}
