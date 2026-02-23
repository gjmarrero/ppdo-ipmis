<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectBeneficiaryCreateRequest;
use App\Http\Resources\ProjectBeneficiaryPivotResource;
use App\Models\ProjectBeneficiaryPivot;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectBeneficiaryPivotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        $project_beneficiary_types = ProjectBeneficiaryPivot::all();
        return ProjectBeneficiaryPivotResource::collection($project_beneficiary_types);
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
    public function store(ProjectBeneficiaryCreateRequest $request)
    {
        $data = $request->validated();

        $fundsource = ProjectBeneficiaryPivot::create($data);

        return new ProjectBeneficiaryPivotResource($fundsource);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectBeneficiaryPivot $projectBeneficiaryPivot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectBeneficiaryPivot $projectBeneficiaryPivot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectBeneficiaryPivot $projectBeneficiaryPivot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectBeneficiaryPivot $projectBeneficiaryPivot)
    {
        //
    }

    public function fetchByProjectType($project_type_id){
        $beneficiary_types = ProjectBeneficiaryPivot::where('project_type_id',$project_type_id)->get();

        return ProjectBeneficiaryPivotResource::collection($beneficiary_types);
    }
}
