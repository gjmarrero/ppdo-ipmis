<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectTypeCreateRequest;
use App\Http\Resources\ProjectTypeResource;
use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    public function store(ProjectTypeCreateRequest $request){
        $data = $request->validated();
        $project_type = ProjectType::create($data);
        return new ProjectTypeResource($project_type);
    }

    public function update(ProjectTypeCreateRequest $request, ProjectType $projectType){
        $data = $request->validated();
        $projectType->update($data);
        return new ProjectTypeResource($projectType);
    }
    public function beneficiaryTypes(ProjectType $projectType){
        return response()->json([
            'beneficiary_types' => $projectType->beneficiaryTypes()->get()
        ]);
    }

    public function destroy(ProjectType $projectType, Request $request){
        $projectType->delete();

        return response() -> json([
            'message' => 'Project type successfully deleted'
        ]);
    }
}
