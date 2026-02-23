<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OdsuCreateRequest;
use App\Http\Resources\OdsuResource;
use App\Models\Odsu;
use Illuminate\Http\Request;

class OdsuController extends Controller
{
    public function index(Request $request){
        $odsus = Odsu::all();
        return OdsuResource::collection($odsus);
    }

    public function store(OdsuCreateRequest $request){
        $data = $request->validated();
        $odsu = Odsu::create($data);
        return new OdsuResource($odsu);
    }

    public function update(Odsu $odsu, OdsuCreateRequest $request){
        $data = $request->validated();
        $odsu->update($data);
        return new OdsuResource($odsu);
    }

    public function destroy(Odsu $odsu, Request $request){
        $odsu->delete();

        return response() -> json([
            'message' => 'Office-division successfully deleted'
        ]);
    }
}
