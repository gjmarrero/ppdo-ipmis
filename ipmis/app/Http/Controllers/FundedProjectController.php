<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\FundedProjectCreateRequest;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Resources\FundedProjectResource;
use App\Http\Resources\FundsourceResource;
use App\Http\Resources\ProjectCompleteResource;
use App\Http\Resources\ProjectResource;
use App\Models\FundedProject;
use App\Models\Fundsource;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FundedProjectController extends Controller
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

    public function get_funded_projects(Request $request)
    {
        return ProjectResource::collection(
            $request->user()->projects()->where('status', 'funded')->get()
        );
    }
    public function store(FundedProjectCreateRequest $request)
    {
        $validated_data = $request->validated();

        $project_data = array_merge(
            Arr::only($validated_data, [
                'title',
                'status',
                'input_type',
            ]),
            ['user_id' => Auth::id()]
        );

        if (empty($validated_data['project_id'])) {
            $validated_data['project_id'] = null;
        }

        if (!empty($validated_data['project_id'])) {
            unset($project_data['input_type']);
        }

        $project = Project::updateOrCreate(
            ['id' => $validated_data['project_id']],
            $project_data
        );

        $funded_data = [
            'project_id' => $validated_data['project_id'] ?? $project->id,
            'number' => $validated_data['number'],
            'reference_number' => $validated_data['reference_number'],
            'approved_cost' => $validated_data['approved_cost'],
            'fundsource_id' => $validated_data['fundsource_id'],
            'has_supplemental_budget' => $validated_data['has_supplemental_budget'],
            'sb_number' => $validated_data['sb_number'],
            'is_admin' => $validated_data['is_admin'],
            'year_funded' => $validated_data['year_funded'],
            'user_id' => Auth::id(),
        ];

        $funded_project = FundedProject::updateOrCreate($funded_data);

        $validated_loc_data = $request->validate([
            'locations.*.municipality_id' => 'nullable',
            'locations.*.barangay_id' => 'nullable',
            'locations.*.sitio' => 'nullable',
        ]);

        foreach ($validated_loc_data['locations'] as $location) {
            $project->locations()->updateOrCreate($location);
        }

        return new FundedProjectResource($funded_project);
    }

    public function show(FundedProject $funded_project)
    {
        $funded_project->load([
            'project' => [
                'locations',
                'latestValidation.validation_responsible_people',
                'latestValidation.beneficiaries.beneficiary_sdds',
                'latestValidation.validation_images',
                'latestValidation.validation_supporting_documents',
            ],
            'latestSiteProblem',
            'latestSiteProblem.archive',
            'site_problems',
            'site_problems.archive',
            'preengineering_statuses',
            'environmental_clearances.environmental_clearance_files',
            'implementations'
        ]);

        return new FundedProjectResource($funded_project);
    }

    public function update(FundedProject $fundedProject, FundedProjectCreateRequest $request)
    {
        $validated_data = $request->validated();

        $funded_data = [
            'number' => $validated_data['number'],
            'reference_number' => $validated_data['reference_number'],
            'approved_cost' => $validated_data['approved_cost'],
            'fundsource_id' => $validated_data['fundsource_id'],
            'sb_number' => $validated_data['sb_number'],
            'is_admin' => $validated_data['is_admin'],
            'year_funded' => $validated_data['year_funded'],
            'user_id' => Auth::id(),
        ];

        $fundedProject->update($funded_data);

        $project_data = array_merge(
            Arr::only($validated_data, ['title', 'status', 'input_type']),
            ['user_id' => Auth::id()]
        );

        $project = Project::updateOrCreate(
            ['id' => $validated_data['project_id'] ?? null],
            $project_data
        );

        $fundedProject->project()->associate($project);
        $fundedProject->save();

        if (!empty($validated_data['locations'])) {
            // Prepare submitted locations
            $submittedLocations = collect($validated_data['locations'])
                ->map(fn($loc) => [
                    'municipality_id' => $loc['municipality_id'] ?? null,
                    'barangay_id' => $loc['barangay_id'] ?? null,
                    'sitio' => $loc['sitio'] ?? null,
                ])
                ->unique() // remove duplicates in submitted payload
                ->values();

            // Get existing locations for this project
            $existingLocations = $project->locations()->get(['id', 'municipality_id', 'barangay_id', 'sitio']);

            // Delete locations that are not in submitted locations
            $toDelete = $existingLocations->filter(function ($existing) use ($submittedLocations) {
                return !$submittedLocations->contains(function ($submitted) use ($existing) {
                    return $submitted['municipality_id'] === $existing->municipality_id
                        && $submitted['barangay_id'] === $existing->barangay_id
                        && $submitted['sitio'] === $existing->sitio;
                });
            });

            if ($toDelete->isNotEmpty()) {
                $project->locations()->whereIn('id', $toDelete->pluck('id'))->delete();
            }

            // Insert new locations that do not already exist
            $toInsert = $submittedLocations->filter(function ($submitted) use ($existingLocations) {
                return !$existingLocations->contains(function ($existing) use ($submitted) {
                    return $submitted['municipality_id'] === $existing->municipality_id
                        && $submitted['barangay_id'] === $existing->barangay_id
                        && $submitted['sitio'] === $existing->sitio;
                });
            });

            if ($toInsert->isNotEmpty()) {
                $project->locations()->insert(
                    $toInsert->map(fn($loc) => [
                        'id' => Str::uuid(), // <-- generate UUID
                        'project_id' => $project->id,
                        'municipality_id' => $loc['municipality_id'],
                        'barangay_id' => $loc['barangay_id'],
                        'sitio' => $loc['sitio'],
                    ])->toArray()
                );
            }
        } else {
            // If no locations submitted, remove all
            $project->locations()->delete();
        }



        return new FundedProjectResource($fundedProject->fresh(['project.locations']));
    }

    public function checkTitle(Request $request)
    {
        $titleQuery = Project::query();
        $numberQuery = FundedProject::query();

        if ($request->filled('title')) {
            $titleQuery->where('title', $request->title);
        }

        if ($request->ignore_id) {
            $titleQuery->where('id', '!=', $request->ignore_id);
        }

        if ($request->filled('number')) {
            $numberQuery->where('number', $request->number);
        }

        if ($request->ignore_funded_id) {
            $numberQuery->where('id', '!=', $request->ignore_funded_id);
        } elseif ($request->ignore_id) {
            // Fallback when frontend only passes project ID in edit mode
            $numberQuery->where('project_id', '!=', $request->ignore_id);
        }

        $titleExists = $request->filled('title') ? $titleQuery->exists() : false;
        $numberExists = $request->filled('number') ? $numberQuery->exists() : false;

        return response()->json([
            'exists' => $titleExists || $numberExists,
            'title_exists' => $titleExists,
            'number_exists' => $numberExists
        ]);
    }


    // public function update(FundedProject $fundedProject, FundedProjectCreateRequest $request)
    // {
    //     $validated_data = $request->validated();

    //     $funded_data = [
    //         'number' => $validated_data['number'],
    //         'reference_number' => $validated_data['reference_number'],
    //         'approved_cost' => $validated_data['approved_cost'],
    //         'fundsource_id' => $validated_data['fundsource_id'],
    //         'sb_number' => $validated_data['sb_number'],
    //         'is_admin' => $validated_data['is_admin'],
    //         'year_funded' => $validated_data['year_funded'],
    //         'user_id' => Auth::id(),
    //     ];

    //     abort_if($project->user_id !== $request->user()->id, 403, "You are not allowed to update this project");

    //     $fundedProject->update($funded_data);

    //     $project_data = array_merge(
    //         Arr::only($validated_data, [
    //             'title',
    //             'status',
    //             'input_type',
    //         ]),
    //         ['user_id' => Auth::id()]
    //     );

    //     if (empty($validated_data['project_id'])) {
    //         $validated_data['project_id'] = null;
    //     }

    //     $project = Project::updateOrCreate(
    //         ['id' => $validated_data['project_id']],
    //         $project_data
    //     );

    //     $locationData = collect($validated_data['locations']);

    //     $project->locations()->upsert(
    //         $locationData->toArray(),
    //         ['id'],
    //         ['sitio', 'barangay_id']
    //     );

    //     $ids = $locationData->pluck('id')->filter()->toArray();
    //     $project->locations()->whereNotIn('id', $ids)->delete();

    //     return new FundedProjectResource($fundedProject);
    // }

    // public function show(FundedProject $funded_project)
    // {
    //     $funded_project->load([
    //         'project',
    //         'project.locations',
    //         'project.latestValidation',
    //         'project.latestValidation.validation_responsible_people',
    //         'project.latestValidation.beneficiaries',
    //         'project.latestValidation.beneficiaries.beneficiary_sdds',
    //         'project.latestValidation.validation_images',
    //         'project.latestValidation.validation_supporting_documents',
    //         'preengineering_statuses',
    //         'environmental_clearances',
    //         'environmental_clearances.environmental_clearance_files'
    //     ]);
    //     return new FundedProjectResource($funded_project);
    // }

    public function countFundedProjects()
    {
        $count_funded_projects = Project::where('status', 'funded')->count();
        return $count_funded_projects;
    }

    public function countValidatedFundedProjects()
    {
        $count_validated_funded_projects = FundedProject::whereHas('project.validations')->count();

        return $count_validated_funded_projects;
    }

    public function countProgrammedFundedProjects()
    {
        $count_programmed_funded_projects = FundedProject::whereHas('preengineering_statuses')->count();
        return $count_programmed_funded_projects;
    }

    public function countProjectsByFundsource()
    {
        $counts = Fundsource::withCount('projects')
            ->get()
            ->map(function ($fundsource) {
                return [
                    'id' => $fundsource->id,
                    'name' => $fundsource->fundsource,
                    'count' => $fundsource->projects_count,
                ];
            });

        return response()->json($counts);
    }

    public function countForEcProcessing()
    {
        $count_for_ec_processing = Project::whereHas(
            'latestFunding.latestPreengineering',
            fn($q) => $q->whereHas('approved_pow')
        )
            ->whereDoesntHave('latestFunding.latestEnvironmentalClearance')
            ->count();

        return response()->json($count_for_ec_processing);
    }

    public function countEcProcessed()
    {
        $count_ec_processed = Project::whereHas('latestFunding.latestEnvironmentalClearance', function ($q) {
            $q->whereNotNull('date_issued');
        })->count();

        return response()->json($count_ec_processed);
    }

    public function countForPreengProcessing()
    {
        $count_for_preeng_processing = Project::whereDoesntHave('latestFunding.latestPreengineering')->count();
        return response()->json($count_for_preeng_processing);
    }

    public function countPreengProcessed()
    {
        $count_preeng_processed = Project::whereHas('latestFunding.latestPreengineering', function ($q) {
            $q->whereNotNull('date_submitted_lce');
        })->count();

        return response()->json($count_preeng_processed);
    }
}


