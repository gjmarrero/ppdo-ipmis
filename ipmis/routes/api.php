<?php

use App\Http\Controllers\ApprovedPowController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BIPTISController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EnvironmentalClearanceCmrController;
use App\Http\Controllers\EnvironmentalClearanceController;
use App\Http\Controllers\FundedProjectController;
use App\Http\Controllers\FundsourceController;
use App\Http\Controllers\ImplementationController;
use App\Http\Controllers\ImplementationMonthlyAccomplishmentController;
use App\Http\Controllers\ImplementationOrderController;
use App\Http\Controllers\OdsuController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PlantillaController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PreengineeringActivityController;
use App\Http\Controllers\PreengineeringStatusController;
use App\Http\Controllers\ProjectBeneficiaryPivotController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SiteProblemController;
use App\Http\Controllers\SpecificScopeOfWorkController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationController;
use App\Http\Resources\OdsuResource;
use App\Models\ApprovedPow;
use App\Models\Archive;
use App\Models\Barangay;
use App\Models\BeneficiaryType;
use App\Models\Division;
use App\Models\ECCertificateType;
use App\Models\ECPambType;
use App\Models\Employee;
use App\Models\Fundsource;
use App\Models\ImplementationOrder;
use App\Models\Municipality;
use App\Models\Odsu;
use App\Models\Office;
use App\Models\Plantilla;
use App\Models\Position;
use App\Models\PreengineeringStatus;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\SpecificScopeOfWork;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/fundsources', [FundsourceController::class, 'index']);
    Route::post('/fundsource', [FundsourceController::class, 'store']);
    Route::get('/fundsource/{fundsource}', [FundsourceController::class, 'show']);
    Route::put('/fundsource/{fundsource}', [FundsourceController::class, 'update']);
    Route::delete('/fundsource/{fundsource}', [FundsourceController::class, 'destroy']);

    Route::get('/offices', [OfficeController::class, 'index']);
    Route::post('/office', [OfficeController::class, 'store']);
    Route::put('/office/{office}', [OfficeController::class, 'update']);
    Route::delete('/office/{office}', [OfficeController::class, 'destroy']);

    Route::post('/project_type', [ProjectTypeController::class, 'store']);
    Route::put('/project_type/{project_type}', [ProjectTypeController::class, 'update']);
    Route::delete('/project_type/{project_type}', [ProjectTypeController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::put('/user/account', [UserController::class, 'updateAccount']);
    Route::put('/user/password', [UserController::class, 'updatePassword']);
    

    Route::get('/odsus', [OdsuController::class, 'index']);
    Route::post('/odsu', [OdsuController::class, 'store']);
    Route::put('/odsu/{odsu}', [OdsuController::class, 'update']);
    Route::delete('/odsu/{odsu}', [OdsuController::class, 'destroy']);

    Route::get('/positions', [PositionController::class, 'index']);
    Route::post('/position', [PositionController::class, 'store']);
    Route::put('/position/{position}', [PositionController::class, 'update']);
    Route::delete('/position/{position}', [PositionController::class, 'destroy']);

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employee', [EmployeeController::class, 'store']);
    Route::put('/employee/{employee}', [EmployeeController::class, 'update']);
    Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy']);


    Route::get('/plantillas', [PlantillaController::class, 'index']);
    Route::post('/plantilla', [PlantillaController::class, 'store']);
    Route::put('/plantilla/{plantilla}', [PlantillaController::class, 'update']);
    Route::delete('/plantilla/{plantilla}', [PlantillaController::class, 'destroy']);

    Route::get('/scopes_of_work', [SpecificScopeOfWorkController::class, 'index']);
    Route::post('/scope_of_work', [SpecificScopeOfWorkController::class, 'store']);
    Route::put('/scope_of_work/{scope_of_work}', [SpecificScopeOfWorkController::class, 'update']);
    Route::delete('scope_of_work/{scope_of_work}', [SpecificScopeOfWorkController::class, 'destroy']);

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/project', [ProjectController::class, 'store']);
    Route::get('/project/{project}', [ProjectController::class, 'show']);
    Route::put('/project/{project}', [ProjectController::class, 'update']);
    Route::delete('/project/{project}', [ProjectController::class, 'destroy']);

    Route::get('/unfundedProjects', [ProjectController::class, 'unfundedProjects']);

    Route::post('/validation', [ValidationController::class, 'store']);
    Route::put('/validation/{validation}', [ValidationController::class, 'update']);
    Route::delete('/validation/{validation}', [ValidationController::class, 'destroy']);

    Route::get('/funded_projects', [FundedProjectController::class, 'index']);
    Route::post('/funded_project', [FundedProjectController::class, 'store']);
    Route::get('/funded_project/{funded_project}', [FundedProjectController::class, 'show']);
    Route::put('/funded_project/{funded_project}', [FundedProjectController::class, 'update']);
    Route::get('/funded_projects/check_title', [FundedProjectController::class, 'checkTitle']);

    Route::get('/pre_engineerings', [PreengineeringStatusController::class, 'index']);
    Route::post('/preengineering', [PreengineeringStatusController::class, 'store']);
    Route::put('/preengineering/{preengineering}', [PreengineeringStatusController::class, 'update']);
    Route::post('/preengineering/qcp_status/{preEngineeringId}', [PreengineeringStatusController::class,'update_qcp_status']);
    Route::post( '/preengineering/review/{preEngineeringId}', [PreengineeringStatusController::class, 'update_review_status']);

    Route::get('/fetch_preengineering_activities/{preengineering_id}', [PreengineeringActivityController::class, 'fetch_preengineering_activities']);
    Route::post('/preengineering_activity', [PreengineeringActivityController::class, 'store']);

    Route::post('/preengineering/approve_pow', [PreengineeringStatusController::class, 'approvePow']);

    Route::post('/site_problem', [SiteProblemController::class, 'store']);
    Route::post('/site_problems/pdc_result', [SiteProblemController::class, 'pdc_result_store']);

    Route::get('/pow_for_approval', [PreengineeringStatusController::class, 'powForApproval']);

    Route::get('/fetch_approved_pow', [PreengineeringStatusController::class, 'fetchApprovedPow']);

    Route::post('/approved_pow', [ApprovedPowController::class, 'store']);

    Route::get('/environmental_clearances', [EnvironmentalClearanceController::class, 'index']);
    Route::post('/environmental_clearance', [EnvironmentalClearanceController::class, 'store']);
    Route::put('/environmental_clearance/{environmental_clearance}', [EnvironmentalClearanceController::class, 'update']);
    Route::put('/update_other_requirements', [EnvironmentalClearanceController::class, 'updateOtherRequirement']);
    Route::post('/environmental_clearance/cmr/{environmentalClearanceId}', [EnvironmentalClearanceCmrController::class, 'store']);
    Route::put('/environmental_clearance/update_cmr/{environmentalClearanceCmrId}', [EnvironmentalClearanceCmrController::class, 'update']);    

    Route::get('/implementations', [ImplementationController::class, 'index']);
    Route::post('/implementation', [ImplementationController::class, 'store']);
    Route::put('/implementation/{implementation}', [ImplementationController::class, 'update']);
    Route::post('/implementation/monthly_targets', [ImplementationController::class, 'store_monthly_targets']);
    Route::get('/implementation/{implementation}/next_accomplishment_month', [ImplementationController::class, 'nextAccomplishmentMonth']);
    Route::get('/implementation/{implementation}/schedule/{duration}', [ImplementationController::class, 'monthlySchedule']);
    Route::post('/accomplishments/batch_update', [ImplementationMonthlyAccomplishmentController::class, 'batchUpdate']);
    Route::post('/implementation/{implementation}/order', [ImplementationOrderController::class, 'store']);
    Route::get('implementation/{implementation}/target', [ImplementationController::class, 'monthlyTarget']);

    Route::get('/reports/projects', [ReportController::class, 'generateProjects']);
    Route::get('/pambProjects', [ReportController::class, 'pambProjects']);

    Route::get('/archives', [ArchiveController::class, 'index']);
    Route::post('/archive', [ArchiveController::class, 'store']);

    Route::get('/project_proposals', [ProjectController::class, 'unfunded_projects']);

    // Route::get('/project/{project}/boards', [BoardController::class, 'index']);
    Route::post('/board', [BoardController::class, 'store']);
    // Route::get('/board/{board}', [BoardController::class, 'show']);
    Route::put('/board/{board}', [BoardController::class, 'update']);
    Route::delete('/board/{board}', [BoardController::class, 'destroy']);

    Route::post('/ticket', [TicketController::class, 'store']);
    Route::get('/ticket/{ticket}', [TicketController::class, 'show']);
    Route::put('/ticket/{ticket}', [TicketController::class, 'update']);
    Route::delete('/ticket/{ticket}', [TicketController::class, 'destroy']);

    Route::post('/ticket/{ticket}/assign', [TicketController::class, 'assign']);

    Route::get('/fetchFundsources', function () {
        return Fundsource::all(['id', 'fundsource as name', 'fundsource_code', 'fundsource_abbrev']);
    });

    Route::get('/fetchProjectTypes', function () {
        return ProjectType::all(['id', 'project_type as name', 'project_type_code']);
    });

    Route::get('/fetchSpecificWorks',  function () {
        return SpecificScopeOfWork::all(['id', 'scope as name']);
    });

    Route::get('/fetchBeneficiaryTypes', function () {
        return BeneficiaryType::all(['id', 'beneficiary_type as name']);
    });
    Route::get('/fetchBeneficiaryTypes/{project_type_id}', [ProjectBeneficiaryPivotController::class, 'fetchByProjectType']);

    Route::get('/project_beneficiaries', [ProjectBeneficiaryPivotController::class, 'index']);
    Route::post('/project_beneficiary', [ProjectBeneficiaryPivotController::class, 'store']);

    Route::get('/project_types/{projectType}/beneficiary_types', [ProjectTypeController::class, 'beneficiaryTypes']);


    Route::get('/municipalities', function () {
        return Municipality::orderBy('municipality')->get(['id', 'municipality as name']);
    });

    Route::get('/municipalities/{municipality}/barangays', function (Municipality $municipality) {
        return $municipality->barangays()->get(['id', 'barangay as name']);
    });

    Route::get('/barangays/{barangay}/sitios', function (Barangay $barangay) {
        return $barangay->sitios()->get(['id', 'sitio as name']);
    });

    Route::get('/e_c_certificate_types', function () {
        return ECCertificateType::all(['id', 'type as name']);
    });

    Route::get('/e_c_pamb_types', function () {
        return ECPambType::all(['id', 'type as name']);
    });

    Route::get('/dashboardCounts', [DashboardController::class, 'dashboardCounts']);

    Route::get('/countProjectProposals', [ProjectController::class, 'countProjectProposals']);
    Route::get('/countValidatedProposals', [ProjectController::class, 'countValidatedProposals']);
    Route::get('/countValidationAssignments/{employee}', [ProjectController::class, 'countValidationAssignments']);

    Route::get('/countFundedProjects', [FundedProjectController::class, 'countFundedProjects']);
    Route::get('/countValidatedFundedProjects', [FundedProjectController::class, 'countValidatedFundedProjects']);
    Route::get('/countProgrammedFundedProjects', [FundedProjectController::class, 'countProgrammedFundedProjects']);
    Route::get('/countProjectsByFundsource', [FundedProjectController::class, 'countProjectsByFundsource']);

    Route::get('/fetchValidationDetails/{funded_project_id}', [ValidationController::class, 'fetchValidationDetails']);
    Route::get('/fetchPreEngineeringDetails/{funded_project_id}', [PreengineeringStatusController::class, 'fetchPreEngineeringDetails']);

    Route::get('/countForEcProcessing', [FundedProjectController::class, 'countForEcProcessing']);
    Route::get('/countEcProcessed', [FundedProjectController::class, 'countEcProcessed']);

    Route::get('/countForPreengProcessing', [FundedProjectController::class, 'countForPreengProcessing']);
    Route::get('/countPreengProcessed', [FundedProjectController::class, 'countPreengProcessed']);

    Route::get('/countMyAssignment/{employee_id}', [PreengineeringStatusController::class, 'countMyAssignment']);

    Route::get('/dashboard/count_by_fundsource', [DashboardController::class, 'countByFundSource']);
    Route::get('/dashboard/count_by_municipality', [DashboardController::class, 'countByMunicipality']);

    Route::get('/project_plans', function () {
        $remoteResponse = Http::get('http://192.168.6.224/project_plans');
        return $remoteResponse->json();
    });

    Route::get('/project_plan/{project_title}', function ($project_title) {
        $params = request()->query();
        $encodedTitle = urlencode($project_title);
        $remoteResponse = Http::get("http://192.168.6.224/project_plan/{$encodedTitle}", $params);
        return response()->json($remoteResponse->json());
    });


    Route::get('/fetchEmployees', function (Request $request) {
        $query = Plantilla::with(['employee', 'position.odsu.office']);

        // Map route "path" → office_id
        $officeMap = [
            'program_of_work' => '1d7d8665-0e4f-49f1-a671-b4ad0892cf27',
            'quality_control' => '1d7d8665-0e4f-49f1-a671-b4ad0892cf27',
            'preengineerings' => '1d7d8665-0e4f-49f1-a671-b4ad0892cf27',
            'environmental_clearances' => '4045fd02-f4d1-46fb-a4dc-30e7d03b43bd',
            'projects' => '9693eb28-98f9-11f0-97f7-74563caf0ec2',
            'funded_projects' => '9693eb28-98f9-11f0-97f7-74563caf0ec2',
        ];

        // If a path parameter is passed, convert it to office_id
        if ($request->filled('path') && isset($officeMap[$request->path])) {
            $officeId = $officeMap[$request->path];
            $query->whereHas('position.odsu.office', function ($q) use ($officeId) {
                $q->where('id', $officeId);
            });
        }

        // Or allow direct filtering by office_id
        if ($request->filled('office_id')) {
            $query->whereHas('position.odsu.office', function ($q) use ($request) {
                $q->where('id', $request->office_id);
            });
        }

        // Transform response for frontend
        $plantillas = $query->get()->map(function ($plantilla) {
            return [
                'id' => $plantilla->employee->id ?? null,
                'name' => trim(($plantilla->employee->first_name ?? '') . ' ' . ($plantilla->employee->last_name ?? '')),
                'position' => $plantilla->position->title ?? null,
                'office' => $plantilla->position->odsu->office->name ?? null,
                'office_id' => $plantilla->position->odsu->office->id ?? null,
            ];
        });

        return $plantillas;
    });

    // Route::get('/fetchEmployees', function () {
    //     return Employee::select('id', DB::raw('CONCAT(first_name, " ", last_name) as name'))->get();
    // });

    Route::get('/fetchOffices', function () {
        return Office::select('id', 'office as name')->get();
    });

    Route::get('/fetchDivisions', function () {
        return Division::select('id', 'division as name')->get();
    });

    Route::get('/fetchOdsus', function () {
        return Odsu::with(['office:id,office', 'division:id,division'])
            ->get(['id', 'office_id', 'division_id'])
            ->map(function ($odsu) {
                return [
                    'id' => $odsu->id,
                    'name' => trim(($odsu->office?->office ?? '') . ' - ' . ($odsu->division?->division ?? '')),
                ];
            });
    });

    Route::get('/fetchPositions', function () {
        return Position::select('id', 'title as name', 'odsu_id')->get();
    });

    Route::get('/fetchArchivedSupplementalBudgets', function () {
        return Archive::select('id', 'number as name')->get();
    });

    // Route::get('/fetchAllEmployees', function () {
    //     return Employee::all(['id', 'last_name', 'first_name', 'middle_name'])
    //         ->map(fn($emp) => [
    //             'id' => $emp->id,
    //             'name' => trim("{$emp->last_name}, {$emp->first_name} {$emp->middle_name}")
    //         ]);
    // });

    // Route::get('/fetchEmployees', {
    //     $remoteResponse = Http::get("http://192.168.2.50/api/getEmployees");
    //     return response()->json($remoteResponse->json());
    // })

});


