<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlantillaCreateRequest;
use App\Http\Resources\PlantillaResource;
use App\Models\Plantilla;
use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    public function index(Request $request){
        $plantillas = Plantilla::all();
        return PlantillaResource::collection($plantillas);
    }

    public function store(PlantillaCreateRequest $request){
        $data = $request->validated();
        $plantilla = Plantilla::create($data);
        return new PlantillaResource($plantilla);
    }

    public function update(Plantilla $plantilla, PlantillaCreateRequest $request){
        $data = $request->validated();
        $plantilla->update($data);
        return new PlantillaResource($plantilla);
    }

    public function destroy(Plantilla $plantilla, Request $request){
        $plantilla->delete();

        return response() -> json([
            "message" => "Plantilla successfully deleted"
        ]);
    }
}
