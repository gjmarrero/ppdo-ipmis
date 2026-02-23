<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EnvironmentalClearanceRequest;
use App\Http\Resources\EnvironmentalClearanceResource;
use App\Http\Resources\FundedProjectResource;
use App\Http\Resources\ProjectCompleteResource;
use App\Models\EnvironmentalClearance;
use App\Models\FundedProject;
use App\Models\Project;
use App\Models\ProjectOtherRequirement;
use App\Models\ValidationOtherRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnvironmentalClearanceController extends Controller
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
                'latestFunding.latestEnvironmentalClearance',
                'latestFunding.latestImplementation'
            ])
                ->whereHas('latestFunding')
                ->orderBy('updated_at', 'desc')
                ->get()
        );
    }
    public function store(EnvironmentalClearanceRequest $request)
    {
        $data = $request->validated();

        $environmental_clearance = $request->user()->environmental_clearances()->create($data);

        $request->validate([
            'files.*' => 'nullable',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $filePath = $file->store('environmental_clearance_files', 'public');

                $environmental_clearance->environmental_clearance_files()->create([
                    'file' => $filePath,
                    'type' => $request->file_types[$index],
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return new EnvironmentalClearanceResource($environmental_clearance);
    }

    public function update(EnvironmentalClearanceRequest $request, $id)
    {
        $data = $request->validated();

        $environmental_clearance = EnvironmentalClearance::findOrFail($id);
        $environmental_clearance->update($data);

        $request->validate([
            'files.*' => 'nullable',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $filePath = $file->store('environmental_clearance_files', 'public');

                $environmental_clearance->environmental_clearance_files()->create([
                    'file' => $filePath,
                    'type' => $request->file_types[$index],
                    'user_id' => Auth::id(),
                ]);
            }
        }
    }

    public function updateOtherRequirement(Request $request)
    {
        $incoming = $request->input('requirements', []);

        if (empty($incoming)) {
            return response()->json(['message' => 'No data received'], 400);
        }

        $projectId = $incoming[0]['project_id'] ?? null;

        if (!$projectId) {
            return response()->json(['message' => 'project_id is required'], 400);
        }

        // Delete removed requirements
        $existingIds = ProjectOtherRequirement::where('project_id', $projectId)->pluck('id')->toArray();
        $incomingIds = collect($incoming)->pluck('id')->filter()->toArray();
        $idsToDelete = array_diff($existingIds, $incomingIds);

        if (!empty($idsToDelete)) {
            ProjectOtherRequirement::whereIn('id', $idsToDelete)->delete();
        }

        foreach ($incoming as $index => $data) {
            // Update or create requirement
            $requirement = null;
            if (!empty($data['id'])) {
                $requirement = ProjectOtherRequirement::find($data['id']);
                $requirement->update([
                    'requirement_type' => $data['requirement_type'],
                    'pamb_type_id' => $data['pamb_type_id'] ?? '',
                    'date_applied' => $data['date_applied'],
                    'date_issued' => $data['date_issued'],
                    'status' => $data['status'],
                    'remarks' => $data['remarks'],
                    'employee_id' => $data['employee_id'],
                    'user_id' => Auth::id(),
                ]);
            } else {
                $requirement = ProjectOtherRequirement::create([
                    'project_id' => $projectId,
                    'requirement_type' => $data['requirement_type'],
                    'pamb_type_id' => $data['pamb_type_id'] ?? '',
                    'date_applied' => $data['date_applied'],
                    'date_issued' => $data['date_issued'],
                    'status' => $data['status'],
                    'remarks' => $data['remarks'],
                    'employee_id' => $data['employee_id'],
                    'user_id' => Auth::id(),
                ]);
            }

            // Handle uploaded files for this requirement
            if ($request->hasFile("requirements.$index.files")) {
                foreach ($request->file("requirements.$index.files") as $file) {
                    $path = $file->store("other_requirements/{$requirement->id}", 'public');

                    // Save file info in DB if you have a files table
                    $requirement->documents()->create([
                        // 'filename' => $file->getClientOriginalName(),
                        'file' => $path
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Other Requirements updated successfully']);
    }

    // public function updateOtherRequirement(Request $request)
    // {
    //     $incoming = $request->all();

    //     if (empty($incoming)) {
    //         return response()->json(['message' => 'No data received'], 400);
    //     }

    //     $projectId = $incoming[0]['project_id'];

    //     if (!$projectId) {
    //         return response()->json(['message' => 'project_id is required'], 400);
    //     }

    //     $existingIds = ProjectOtherRequirement::where('project_id', $projectId)
    //         ->pluck('id')
    //         ->toArray();

    //     $incomingIds = collect($incoming)
    //         ->pluck('id')
    //         ->filter()
    //         ->toArray();

    //     $idsToDelete = array_diff($existingIds, $incomingIds);

    //     if (!empty($idsToDelete)) {
    //         ProjectOtherRequirement::whereIn('id', $idsToDelete)->delete();
    //     }

    //     foreach ($incoming as $data) {

    //         if (!empty($data['id'])) {
    //             ProjectOtherRequirement::where('id', $data['id'])
    //                 ->update([
    //                     'requirement_type' => $data['requirement_type'],
    //                     'pamb_type_id' => $data['pamb_type_id'] ?? '',
    //                     'date_applied' => $data['date_applied'],
    //                     'date_issued' => $data['date_issued'],
    //                     'status' => $data['status'],
    //                     'remarks' => $data['remarks'],
    //                     'employee_id' => $data['employee_id'],
    //                     'user_id' => Auth::id(),
    //                 ]);
    //         }

    //         else {
    //             ProjectOtherRequirement::create([
    //                 'project_id' => $projectId,
    //                 'requirement_type' => $data['requirement_type'],
    //                 'pamb_type_id' => $data['pamb_type_id'] ?? '',
    //                 'date_applied' => $data['date_applied'],
    //                 'date_issued' => $data['date_issued'],
    //                 'status' => $data['status'],
    //                 'remarks' => $data['remarks'],
    //                 'employee_id' => $data['employee_id'],
    //                 'user_id' => Auth::id(),
    //             ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Other Requirements updated successfully']);
    // }

}
