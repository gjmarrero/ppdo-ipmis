<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FundedProject;
use App\Models\Project;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function dashboardCounts(Request $request)
    {
        $user = auth()->user();
        $employeeId = $user->employee->id;

        return response()->json([
            'project_proposals' => Project::all()->count(),
            'validated_proposals' => Project::whereHas('latestValidation', function ($q) {
                $q->whereNotNull('date_validated')
                    ->whereNotNull('date_validation_report');
            })->count(),
            'unvalidated_proposals' => Project::whereHas('latestValidation', function ($q) {
                $q->whereNull('date_validation_report');
            })->orWhereDoesntHave('latestValidation')->count(),

            'funded_proposals' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->where('input_type','proposal')
            ->count(),

            'validation_my_assignments' => Project::whereHas('latestValidation', function ($q) use ($employeeId) {
                $q->whereNull('date_validated')
                    ->whereHas('validation_responsible_people', function ($q2) use ($employeeId) {
                        $q2->where('employee_id', $employeeId);
                    });
            })
            ->count(),
            'unvalidated_proposal' => Project::when($request->yearFunded, function ($query, $yearFunded) {
                $query->where(function ($q) use ($yearFunded) {
                    $q->whereHas('latestValidation', function ($sub) use ($yearFunded) {
                        $sub->whereNull('date_validation_report');
                    })
                        ->orWhereDoesntHave('latestValidation');
                });
            })->count(),

            'funded_projects' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })->count(),

            'validated_funded_projects' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestValidation', function ($query){
                $query->whereNotNull('date_validated');
            })
            ->count(),
            'preengineered_projects' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->wherehas('latestFunding.latestPreengineering', function ($query) {
                $query->whereNotNull('date_submitted_to_lce');
            })
            ->count(),
            'for_preengineering' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereDoesntHave('latestFunding.latestPreengineering')->count(),
            'for_qcp' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->wherehas('latestFunding.latestPreengineering', function ($query) {
                $query->whereNotNull('date_reviewed_pow')
                    ->whereNotNull('date_received_by_qc')
                    ->whereNull('date_qcp_reviewed');
            })
            ->count(),
            'for_review' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestPreengineering', function ($query) {
                $query->whereNotNull('date_reviewed_pow')
                    ->whereNotNull('date_qcp_reviewed')
                    ->whereNull('date_recommended_for_approval');
            })
            ->count(),
            'processed_pow' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestPreengineering', function ($query) {
                $query->whereNotNull('date_submitted_to_lce');
            })
            ->count(),

            'pow_assignments' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestPreengineering', function ($query) use ($employeeId) {
                $query->whereNull('date_reviewed_pow')
                    ->where('employee_id', $employeeId);
            })
            ->count(),

            'qcp_assignments' => Project::wherehas('latestFunding', function ($query) use ($request) {
                if($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestPreengineering', function ($query) use ($employeeId) {
                $query->whereNotNull('date_reviewed_pow')
                    ->whereNull('date_qcp_reviewed')
                    ->where('employee_id_qcp', $employeeId);
            })
            ->count(),

            'for_ec' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestPreengineering.approved_pow')
            ->whereDoesntHave('latestFunding.latestEnvironmentalClearance')
            ->count(),

            'ongoing_ec' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestEnvironmentalClearance', function ($query) {
                $query->whereNotNull('date_application')
                    ->whereNull('date_issued');
            })
            ->count(),

            'processed_ec' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if ($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestEnvironmentalClearance', function ($query) {
                $query->where('status', 'Approved')
                    ->whereNotNull('date_issued');
            })
            ->count(),

            'ec_assignments' => Project::whereHas('latestFunding', function ($query) use ($request) {
                if($request->filled('yearFunded')) {
                    $query->where('year_funded', $request->yearFunded);
                }
            })
            ->whereHas('latestFunding.latestEnvironmentalClearance', function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId)
                    ->whereNull('date_issued');
            })
            ->count(),
        ]);
    }
    public function countByFundSource()
    {
        $counts = FundedProject::select('fundsource_id', DB::raw('count(*) as total'))
            ->with('fundsource:id,fundsource')
            ->groupBy('fundsource_id')
            ->get();

        return response()->json($counts);
    }

    public function countByMunicipality()
    {
        $counts = DB::table('funded_projects')
            ->join('projects', 'funded_projects.project_id', '=', 'projects.id')
            ->join('locations', 'projects.id', '=', 'locations.project_id')
            ->join('barangays', 'locations.barangay_id', '=', 'barangays.id')
            ->join('municipalities', 'barangays.municipality_id', '=', 'municipalities.id')
            ->select('municipalities.municipality', DB::raw('COUNT(funded_projects.id) as total'))
            ->groupBy('municipalities.id', 'municipalities.municipality')
            ->get();

        return response()->json($counts);
    }
}
