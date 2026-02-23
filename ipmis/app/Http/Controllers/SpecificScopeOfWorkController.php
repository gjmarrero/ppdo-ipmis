<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecificScopeOfWorkCreateRequest;
use App\Http\Resources\SpecificScopeOfWorkResource;
use App\Models\SpecificScopeOfWork;
use Illuminate\Http\Request;

class SpecificScopeOfWorkController extends Controller
{
    public function index(Request $request){
        $scopes_of_work = SpecificScopeOfWork::all();
        return SpecificScopeOfWorkResource::collection($scopes_of_work);
    }

    public function store(SpecificScopeOfWorkCreateRequest $request){
        $data = $request->validated();
        $plantilla = SpecificScopeOfWork::create($data);
        return new SpecificScopeOfWorkResource($plantilla);
    }

    public function update(SpecificScopeOfWork $scope_of_work, SpecificScopeOfWorkCreateRequest $request){
        $data = $request->validated();
        $scope_of_work->update($data);
        return new SpecificScopeOfWorkResource($scope_of_work);
    }

    public function destroy(SpecificScopeOfWork $scope_of_work, Request $request){
        $scope_of_work->delete();

        return response() -> json([
            "message" => "Scope of work successfully deleted"
        ]);
    }
}
