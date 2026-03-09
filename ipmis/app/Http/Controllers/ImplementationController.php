<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImplementationCreateRequest;
use App\Http\Resources\FundedProjectResource;
use App\Http\Resources\ImplementationResource;
use App\Http\Resources\ProjectCompleteResource;
use App\Models\FundedProject;
use App\Models\Implementation;
use App\Models\ImplementationMonthlyAccomplishment;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImplementationController extends Controller
{
    public function index(Request $request)
    {
        // return FundedProjectResource::collection(
        //     FundedProject::with([
        //         'project',
        //         'implementations',
        //     ])
        //         ->orderBy('created_at', 'desc')
        //         ->get()
        // );

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
            'latestFunding.latestImplementation',
            'latestFunding.latestImplementation.implementation_monthly_accomplishments',
            'latestFunding.latestImplementation.orders',
            'latestFunding.latestImplementation.orders.files',
            'latestFunding.latestImplementation.inspections',
            'latestFunding.latestImplementation.inspections.files'
        )
            ->where('status', 'funded')
            ->orderBy('updated_at', 'desc')
            ->get());
    }

    public function store(ImplementationCreateRequest $request)
    {
        $data = $request->validated();

        // $implementation = Implementation::create($data);
        $implementation = $request->user()->implementations()->create($data);

        return new ImplementationResource($implementation);
    }

    public function update(ImplementationCreateRequest $request, $id)
    {
        $data = $request->validated();

        $implementation = Implementation::findOrFail($id);
        $implementation->update($data);
    }

    public function getExpectedSchedule($id, $duration)
    {
        $implementation = Implementation::findOrFail($id);
        $startDate = Carbon::parse($implementation->date_start);
        $months = [];

        $monthsCount = max(1, ceil($duration / 30));

        for ($i = 0; $i < $monthsCount; $i++) {
            $months[] = $startDate->copy()
                ->addMonths($i)
                ->startOfMonth()
                ->format('Y-m-d');
        }

        return response()->json(['months' => $months]);
    }

    public function monthlySchedule(Implementation $implementation, $duration)
    {
        $dateStart = Carbon::parse($implementation->date_start);
        $durationInDays = (int) floatval($duration);

        $targetCompletion = $dateStart->copy()->addDays($durationInDays);

        $months = [];
        $current = $dateStart->copy()->startOfMonth();

        while ($current <= $targetCompletion) {
            $months[] = $current->toDateString();
            $current->addMonth();
        }

        return response()->json([
            'date_start' => $dateStart->toDateString(),
            'target_completion' => $targetCompletion->toDateString(),
            'months' => $months
        ]);

    }

    public function monthlyTarget(Implementation $implementation)
    {
        $monthlyTargets = $implementation->implementation_monthly_accomplishments()
            ->where('is_active', true)
            ->orderBy('month', 'asc')
            ->get(['id', 'month', 'target']);

        return response()->json([
            'implementation_id' => $implementation->id,
            'targets' => $monthlyTargets,
        ]);
    }


    public function store_monthly_targets(Request $request)
    { {
            $validated = $request->validate([
                'implementation_id' => 'required|exists:implementations,id',
                'targets' => 'required|array',
                'targets.*.rawMonth' => 'required|date',
                'targets.*.target' => 'required|numeric',
            ]);

            $implementation_id = $validated['implementation_id'];
            $currentActive = ImplementationMonthlyAccomplishment::where('implementation_id', $implementation_id)
                ->where('is_active', true)
                ->pluck('month')
                ->toArray();
            $sentMonths = array_column($validated['targets'], 'rawMonth');

            $monthsChanged = !empty(array_diff($currentActive, $sentMonths)) || !empty(array_diff($sentMonths, $currentActive));

            if ($monthsChanged) {
                // Deactivate current active schedules
                ImplementationMonthlyAccomplishment::where('implementation_id', $implementation_id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                // Create new active schedules
                foreach ($validated['targets'] as $target) {
                    ImplementationMonthlyAccomplishment::create([
                        'implementation_id' => $implementation_id,
                        'month' => $target['rawMonth'],
                        'target' => $target['target'],
                        'user_id' => Auth::id(),
                        'is_active' => true
                    ]);
                }
            } else {
                // Update existing active schedules
                foreach ($validated['targets'] as $target) {
                    ImplementationMonthlyAccomplishment::updateOrCreate(
                        [
                            'implementation_id' => $implementation_id,
                            'month' => $target['rawMonth'],
                            'is_active' => true
                        ],
                        [
                            'target' => $target['target'],
                            'user_id' => Auth::id()
                        ]
                    );
                }
            }

            return response()->json(['message' => 'Targets saved successfully']);
        }
    }

    public function nextAccomplishmentMonth(Implementation $implementation)
    {
        $start = Carbon::parse($implementation->date_start)->copy()->startOfMonth();
        $end = now()->startOfMonth();

        $lastAccomplishment = $implementation->implementation_monthly_accomplishments()->orderBy('month', 'desc')->first();

        if ($lastAccomplishment) {
            $next = Carbon::parse($lastAccomplishment->month)->addMonth();
        } else {
            $next = $start;
        }

        if ($next > $end) {
            return response()->json(['month' => null]);
        }

        return response()->json(['month' => $next->format('Y-m')]);
    }
}
