<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PowForApprovalCreateRequest;
use App\Http\Requests\PreengineeringStatusCreateRequest;
use App\Http\Requests\PreengineeringStatusQcpCreateRequest;
use App\Http\Requests\PreengineeringStatusReviewCreateRequest;
use App\Http\Resources\FundedProjectResource;
use App\Http\Resources\PreengineeringStatusResource;
use App\Http\Resources\ProjectCompleteResource;
use App\Models\FundedProject;
use App\Models\PreengineeringStatus;
use App\Models\Project;
use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreengineeringStatusController extends Controller
{
    public function index(Request $request)
    {

        return ProjectCompleteResource::collection(
            Project::with([
                'locations.barangay.municipality',
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
                'latestFunding.preengineering_statuses',
                'latestFunding.environmental_clearances',
                'latestFunding.latestSiteProblem',
                'latestFunding.fundsource',
                'latestFunding.latestPreengineering',
                'latestFunding.latestPreengineering.preengineering_images',
                'latestFunding.latestEnvironmentalClearance',
                'latestFunding.latestImplementation'
            ])
                ->whereHas('latestFunding')
                ->orderBy('updated_at', 'desc')
                ->get()
        );
    }
    public function store(PreengineeringStatusCreateRequest $request)
    {
        $data = $request->validated();

        $preengineering_data = Arr::except($data, [
            'scopes',
        ]);

        $project_data = $preengineering_status = $request->user()->preengineering_statuses()->create($preengineering_data);

        foreach ($data['scopes'] as $scope) {
            $preengineering_status->scopes()->create([
                'scope_of_work_id' => $scope['scope_of_work_id'],
                'description' => $scope['description'],
                'user_id' => Auth::id()
            ]);
        }

        $request->validate([
            'images' => 'nullable|array',
            'images.*.file' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'images.*.lat' => 'required|string',
            'images.*.long' => 'required|string',
        ]);

        if ($request->hasFile('images.*.file')) {
            foreach ($request->images as $imageData) {
                $path = $imageData['file']->store('preengineering_images', 'public');

                $preengineering_status->preengineering_images()->create([
                    'file' => $path,
                    'lat' => $imageData['lat'],
                    'long' => $imageData['long'],
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return new PreengineeringStatusResource($preengineering_status);
    }

    public function update(PreengineeringStatusCreateRequest $request, $id)
    {
        $data = $request->validated();

        $preengineering = PreengineeringStatus::with('scopes')->findOrFail($id);

        $preengineering->update(
            Arr::except($data, ['scopes'])
        );

        $incomingScopes = collect($data['scopes'] ?? []);

        $incomingIds = $incomingScopes->pluck('id')->filter()->values();

        $preengineering->scopes()
            ->whereNotIn('id', $incomingIds)
            ->delete();

        foreach ($incomingScopes as $scope) {
            $preengineering->scopes()->updateOrCreate(
                ['id' => $scope['id'] ?? null],
                [
                    'scope_of_work_id' => $scope['scope_of_work_id'],
                    'description' => $scope['description'],
                    'user_id' => Auth::id(),
                ]
            );
        }

        if ($request->hasFile('images.*.file')) {
            foreach ($request->images as $imageData) {
                $path = $imageData['file']->store('preengineering_images', 'public');

                $preengineering->preengineering_images()->create([
                    'file' => $path,
                    'lat' => $imageData['lat'],
                    'long' => $imageData['long'],
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return new PreengineeringStatusResource(
            $preengineering->load('scopes')
        );
    }

    public function fetchPreEngineeringDetails($funded_project_id)
    {
        $preEngineeringDetails = PreengineeringStatus::with(['employee', 'latestActivity', 'latestActivity.employee'])
            ->where('funded_project_id', $funded_project_id)
            ->get();
        return PreengineeringStatusResource::collection($preEngineeringDetails);
    }

    public function powForApproval(Request $request)
    {
        return ProjectCompleteResource::collection(Project::with(
            'locations.barangay.municipality',
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
            'latestFunding.preengineering_statuses',
            'latestFunding.latestSiteProblem',
            'latestFunding.fundsource',
            'latestFunding.latestPreengineering',
            'latestFunding.latestEnvironmentalClearance',
            'latestFunding.latestImplementation'
        )
            ->where('status', 'funded')
            ->whereHas('latestFunding', function ($query) {
                $query->whereHas('latestPreengineering', function ($query) {
                    $query->where([['date_submitted_to_lce', '<>', null], ['date_approved_lce', null]]);
                });
            })
            ->orderBy('updated_at', 'desc')
            ->get());


    }

    public function approvePow(PowForApprovalCreateRequest $request)
    {
        $preengineering = PreengineeringStatus::findOrFail($request->preengineering_id);

        $data = $request->validated();

        $preengineering->update($data);
    }

    public function fetchApprovedPow(Request $request)
    {
        return ProjectCompleteResource::collection(Project::with(
            'locations.barangay.municipality',
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
            'latestFunding.preengineering_statuses',
            'latestFunding.latestSiteProblem',
            'latestFunding.fundsource',
            'latestFunding.latestPreengineering',
            'latestFunding.latestEnvironmentalClearance',
            'latestFunding.latestImplementation'
        )
            ->where('status', 'funded')
            ->whereHas('latestFunding', function ($query) {
                $query->whereHas('latestPreengineering', function ($query) {
                    $query->where([['date_approved_lce', '<>', null]])
                        ->whereDoesntHave('approved_pow');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->get());
    }

    public function countMyAssignment($employee_id)
    {
        $count_my_assignments = Project::whereHas('latestFunding.latestPreengineering', function ($q) use ($employee_id) {
            $q->where([['employee_id', $employee_id], ['date_submitted_lce', null]]);
        })->count();

        return response()->json($count_my_assignments);
    }

    public function update_qcp_status(PreengineeringStatusQcpCreateRequest $request, $preengineering_id)
    {
        $preengineering = PreengineeringStatus::findOrFail($preengineering_id);

        $data = $request->validated();
        $preengineering->update($data);

        return new PreengineeringStatusResource(
            $preengineering->load('scopes')
        );
    }

    public function update_review_status(PreengineeringStatusReviewCreateRequest $request, $preengineering_id)
    {
        $preengineering = PreengineeringStatus::findOrFail($preengineering_id);
        $data = $request->validated();
        $preengineering->update($data);

        return new PreengineeringStatusResource(
            $preengineering->load('scopes')
        );
    }
}
