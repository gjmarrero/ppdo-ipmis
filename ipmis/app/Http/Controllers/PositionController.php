<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionCreateRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index(Request $request){
        $positions = Position::with('odsu')->get();
        return PositionResource::collection($positions);
    }

    public function store(PositionCreateRequest $request){
        $data = $request->validated();
        $position = Position::create($data);
        return new PositionResource($position);
    }

    public function update(Position $position, PositionCreateRequest $request){
        $data = $request->validated();
        $position->update($data);
        return new PositionResource($position);
    }

    public function destroy(Position $position, Request $request){
        $position->delete();

        return response() -> json([
            'message' => 'Position successfully deleted'
        ]);
    }
}
