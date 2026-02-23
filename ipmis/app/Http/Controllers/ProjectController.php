<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Resources\ProjectCompleteResource;
use App\Http\Resources\ProjectProposalFundedSummaryResource;
use App\Http\Resources\ProjectResource;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(
            'locations.barangay.municipality',
            'locations',
            'user',
            'fundsource',
            'project_type',
            'otherRequirements',
            'latestValidation',
            'latestValidation.validation_responsible_people',
            'latestValidation.beneficiaries.beneficiary_sdds',
            'latestValidation.validation_images',
            'latestValidation.validation_map_images',
            'latestValidation.validation_supporting_documents',
            'latestValidation.validation_other_requirements',
            'latestFunding',
            'latestFunding.fundsource',
            'latestFunding.latestPreengineering',
            'latestFunding.latestEnvironmentalClearance',
            'latestFunding.latestImplementation'
        );

        if ($request->filled('yearFunded')) {
            $query->whereHas('latestFunding', function ($q) use ($request) {
                $q->where('year_funded', $request->yearFunded);
            });
        }

        return ProjectCompleteResource::collection(
            $query->orderBy('updated_at', 'desc')->get()
        );
        
        // return ProjectCompleteResource::collection(Project::with(
        //     'locations.barangay.municipality',
        //     'locations',
        //     'user',
        //     'fundsource',
        //     'project_type',
        //     'otherRequirements',
        //     'latestValidation',
        //     'latestValidation.validation_responsible_people',
        //     'latestValidation.beneficiaries.beneficiary_sdds',
        //     'latestValidation.validation_images',
        //     'latestValidation.validation_map_images',
        //     'latestValidation.validation_supporting_documents',
        //     'latestValidation.validation_other_requirements',
        //     'latestFunding',
        //     'latestFunding.fundsource',
        //     'latestFunding.latestPreengineering',
        //     'latestFunding.latestEnvironmentalClearance',
        //     'latestFunding.latestImplementation'
        // )
        //     ->orderBy('updated_at', 'desc')
        //     ->get());
    }

    public function fundedProjects(Request $request)
    {
        return ProjectResource::collection($request->user()->projects);
    }

    public function store(ProjectCreateRequest $request)
    {
        $data = $request->validated();

        $project_data = Arr::only($data, [
            'title',
            'status',
            'cost',
            'fundsource_id',
            'project_type_id',
            'input_type',
        ]);

        $project = $request->user()->projects()->create($project_data);

        foreach ($data['locations'] as $location) {
            $project->locations()->create($location);
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('project_supporting_documents', 'public');
                $project->documents()->create([
                    'file' => $filePath,
                    'project_id' => $project->id,
                    'user_id' => Auth::id()
                ]);
            }
        }
        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        $project->load([
            'locations.barangay.municipality',
            'locations',
            'user',
            'fundsource',
            'latestValidation',
            'latestValidation.validation_responsible_people',
            'latestValidation.beneficiaries.beneficiary_sdds',
            'latestValidation.validation_images',
            'latestValidation.validation_map_images',
            'latestValidation.validation_supporting_documents',
            'latestValidation.validation_other_requirements',
            'latestFunding',
            'latestFunding.fundsource',
            'latestFunding.latestPreengineering',
            'latestFunding.latestEnvironmentalClearance',
            'latestFunding.latestImplementation',
        ]);

        return new ProjectCompleteResource($project);
    }


    // public function show(Project $project)
    // {
    //     $project->load('locations.barangay.municipality', 'documents');
    //     return new ProjectResource($project);

    // }

    public function unfundedProjects(Request $request)
    {
        return ProjectResource::collection($request->user()->projects);
    }

    public function update(Project $project, ProjectCreateRequest $request)
    {
        $data = $request->validated();

        $project_data = Arr::only($data, [
            'title',
            'status',
            'cost',
            'fundsource_id',
            'project_type_id',
        ]);

        // abort_if($project->user_id !== $request->user()->id, 403, "You are not allowed to update this project");

        $project->update($project_data);

        $locationData = collect($data['locations']);

        $project->locations()->upsert(
            $locationData->toArray(),
            ['id'],
            ['sitio', 'barangay_id', 'municipality_id']
        );

        $ids = $locationData->pluck('id')->filter()->toArray();
        $project->locations()->whereNotIn('id', $ids)->delete();

        return new ProjectResource($project);
    }

    public function destroy(Project $project, Request $request)
    {
        abort_if($project->user_id !== $request->user()->id, 403, "You are not allowed to delete this project");

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    public function countProjectProposals(Request $request)
    {
        return Project::when($request->yearFunded, function ($query, $yearFunded) {
            $query->whereHas('latestFunding', function ($q) use ($yearFunded) {
                $q->where('year_funded', $yearFunded);
            });
        })->count();
    }

    public function countValidatedProposals()
    {
        $count_validated_proposals = Project::whereHas('validations', function ($q) {
            $q->whereNotNull('date_validated')
                ->whereNotNull('date_validation_report');
        })->count();
        return $count_validated_proposals;
    }

    // public function countValidationAssignments($employee)
    // {
    //     $count_validation_assignments = Project::whereHas('validations,validations.validation_responsible_people', function ($q) use ($employee) {
    //         $q->whereNotNull('date_validated')
    //             ->where('validations_responsible_people.employee_id',$employee);
    //     })->count();
    //     return $count_validation_assignments;
    // }
    public function countValidationAssignments($employee)
    {
        $count_validation_assignments = Project::whereHas('validations', function ($q) use ($employee) {
            $q->whereNull('date_validated')
                ->whereHas('validation_responsible_people', function ($q2) use ($employee) {
                    $q2->where('employee_id', $employee);
                });
        })->count();

        return $count_validation_assignments;
    }

    public function unfunded_projects(Request $request)
    {
        return Project::select('id', 'title as name')
            ->where('status', '<>', 'funded')
            ->get();
    }
}
