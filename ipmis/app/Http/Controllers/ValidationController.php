<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidationCreateRequest;
use App\Http\Resources\FundedProjectResource;
use App\Http\Resources\ValidationResource;
use App\Models\FundedProject;
use App\Models\Project;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    public function store(ValidationCreateRequest $request)
    {
        $data = $request->validated();

        $validation_data = Arr::only($data, [
            'project_id',
            'date_assigned',
            'date_validated',
            'date_validation_report',
            'actions_recommendations',
            'present_during_validation',
            'remarks',
            'is_pamb',
        ]);

        $validation = $request->user()->validations()->create($validation_data);

        $validation->project()->update([
            'project_type_id' => $data['project_type_id'],
        ]);

        if (!empty($data['responsible_persons']) && is_iterable($data['responsible_persons'])) {
            foreach ($data['responsible_persons'] as $responsible_person) {
                $validation->validation_responsible_people()->create([
                    'user_id' => Auth::id(),
                    'employee_id' => $responsible_person,
                ]);
            }
        }

        if (!empty($request->beneficiaries) && is_iterable($request->beneficiaries)) {
            foreach ($request->input('beneficiaries', []) as $beneficiaryTypeId => $value) {
                $beneficiaries = $validation->beneficiaries()->create([
                    'beneficiary_type_id' => $beneficiaryTypeId,
                    'beneficiary' => $value,
                ]);
                if ($beneficiaryTypeId == '35b1570b-7ae8-4be6-8992-5cfccf1a6d29' || $beneficiaryTypeId === 'e26daffe-7411-4f1b-bc5c-f94b0101fc51') {
                    $beneficiaries->beneficiary_sdds()->create([
                        'female' => $request->input('number_of_females'),
                        'male' => $request->input('number_of_males'),
                    ]);
                }
            }
        }
        $project = Project::findOrFail($data['project_id']);

        if (!empty($data['selectedRequirementTypes']) && is_iterable($data['selectedRequirementTypes'])) {
            foreach ($data['selectedRequirementTypes'] as $requirementId) {
                $project->otherRequirements()->create([
                    'requirement_type' => $requirementId,
                    'pamb_type_id' => $requirementId === 'pamb_clearance' ? ($data['pamb_type'] ?? null) : null,
                    'user_id' => Auth::id()
                ]);
            }
        }

        $request->validate([
            'files.*' => 'nullable',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file(key: 'files') as $file) {
                $filePath = $file->store('validation_supporting_documents', 'public');

                $validation->validation_supporting_documents()->create([
                    'file' => $filePath,
                    'validation_id' => $validation->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        if ($request->hasFile('images.*.file')) {
            foreach ($request->images as $imageData) {
                $path = $imageData['file']->store('validation_images', 'public');

                $validation->validation_images()->create([
                    'file' => $path,
                    'lat' => $imageData['lat'],
                    'long' => $imageData['long'],
                    'user_id' => Auth::id(),
                ]);
            }
        }

        if ($request->hasFile('mapImages')) {
            foreach ($request->mapImages as $mapImage) {
                $path = $mapImage->store('validation_map_images', 'public');

                $validation->validation_map_images()->create([
                    'file' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return new ValidationResource($validation);
    }

    public function update(ValidationCreateRequest $request, $id)
    {
        $data = $request->validated();

        $validation_data = Arr::only($data, [
            'project_id',
            'date_assigned',
            'date_validated',
            'date_validation_report',
            'actions_recommendations',
            'present_during_validation',
            'remarks',
        ]);

        $validation = Validation::findOrFail($id);

        $validation->update($validation_data);

        $validation->project()->update([
            'project_type_id' => $data['project_type_id'],
        ]);

        if (!empty($data['responsible_persons']) && is_iterable($data['responsible_persons'])) {
            $newEmployeeIds = collect($data['responsible_persons'])->filter()->values();

            $existingEmployeeIds = $validation->validation_responsible_people()->pluck('employee_id');

            $toAdd = $newEmployeeIds->diff($existingEmployeeIds);
            $toDelete = $existingEmployeeIds->diff($newEmployeeIds);

            if ($toDelete->isNotEmpty()) {
                $validation->validation_responsible_people()
                    ->whereIn('employee_id', $toDelete)
                    ->delete();
            }

            foreach ($toAdd as $employeeId) {
                $validation->validation_responsible_people()->create([
                    'user_id' => Auth::id(),
                    'employee_id' => $employeeId,
                ]);
            }
        }

        if (!empty($request->beneficiaries) && is_iterable($request->beneficiaries)) {
            foreach ($request->input('beneficiaries', []) as $beneficiaryTypeId => $value) {
                $beneficiary = $validation->beneficiaries()->updateOrCreate(
                    [
                        'beneficiary_type_id' => $beneficiaryTypeId,
                    ],
                    [
                        'beneficiary' => $value,
                    ]
                );

                if ($beneficiaryTypeId == '35b1570b-7ae8-4be6-8992-5cfccf1a6d29' || $beneficiaryTypeId === 'e26daffe-7411-4f1b-bc5c-f94b0101fc51') {
                    $beneficiary->beneficiary_sdds()->updateOrCreate(
                        [],
                        [
                            'female' => $request->input('number_of_females'),
                            'male' => $request->input('number_of_males'),
                        ]
                    );
                }
            }
        }

        $project = Project::findOrFail($data['project_id']);
        if (!empty($data['selectedRequirementTypes']) && is_iterable($data['selectedRequirementTypes'])) {
            $existing = $project->otherRequirements()->pluck('requirement_type')->toArray();
            $submitted = $data['selectedRequirementTypes'];
            $toAdd = array_diff($submitted, $existing);
            $toDelete = array_diff($existing, $submitted);

            if (!empty($toDelete)) {
                $project->otherRequirements()
                    ->whereIn('requirement_type', $toDelete)
                    ->delete();
            }

            foreach ($toAdd as $requirementId) {
                $project->otherRequirements()->create([
                    'requirement_type' => $requirementId,
                    'user_id' => Auth::id(),
                ]);
            }
        }


        $request->validate([
            'files.*' => 'nullable',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file(key: 'files') as $file) {
                $filePath = $file->store('validation_supporting_documents', 'public');

                $validation->validation_supporting_documents()->create([
                    'file' => $filePath,
                    'validation_id' => $validation->id,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        if ($request->hasFile('images.*.file')) {
            foreach ($request->images as $imageData) {
                $path = $imageData['file']->store('validation_images', 'public');

                $validation->validation_images()->create([
                    'file' => $path,
                    'lat' => $imageData['lat'],
                    'long' => $imageData['long'],
                    'user_id' => Auth::id(),
                ]);
            }
        }

        if ($request->hasFile('mapImages')) {
            foreach ($request->mapImages as $mapImage) {
                $path = $mapImage->store('validation_map_images', 'public');

                $validation->validation_map_images()->create([
                    'file' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return new ValidationResource($validation);
    }

    public function fetchValidationDetails($funded_project_id)
    {
        $validationDetails = FundedProject::with('project.validations', 'project.validations.beneficiaries')->find($funded_project_id);
        return new FundedProjectResource($validationDetails);
    }
}
