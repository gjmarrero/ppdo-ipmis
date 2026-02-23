<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;


class ReportController extends Controller
{
    public function generateProjects()
    {
        $template = new TemplateProcessor(storage_path('app/templates/projects.docx'));

        $template->setValue('date', now()->toDateString());

        $projects = ProjectResource::collection(Project::all());

        $template->cloneRow('name', count($projects));

        foreach ($projects as $index => $project) {
            $i = $index + 1;
            $template->setValue("name#{$i}", $project['title']);
            $locations = $project->locations->map(function ($loc) {
                return "{$loc->sitio}, {$loc->barangay->barangay}, {$loc->barangay->municipality->municipality}";
            })->implode("\n");
            $template->setValue("location#{$i}", str_replace("\n", '<w:br/>', $locations));
            $template->setValue("cost#{$i}", $project['cost']);
            $template->setValue("status#{$i}", $project['status']);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        $template->saveAs($tempFile);

        return response()->download($tempFile, 'report.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    public function pambProjects(Request $request)
    {
        $query = Project::query();

        $query->whereHas('latestValidation.validation_other_requirements', fn ($q) => $q->where('requirement_type', 'pamb_clearance'));

        if ($request->filled('yearFunded')) {
            $query->whereHas(
                'latestFunding',
                fn ($q) => $q->where('year_funded', $request->yearFunded)
            );
        }

        if ($request->filled('pambArea')) {
            $query->whereHas(
                'latestValidation.validation_other_requirements',
                fn ($q) => $q->where('pamb_type_id', $request->pambArea)
            );
        }

        if ($request->filled('pambClearanceStatus')) {
            $query->whereHas(
                'latestValidation.validation_other_requirements',
                fn ($q) => $q->where('status', $request->pambClearanceStatus)
            );
        }

        $projects = ProjectResource::collection(
            $query->with('latestValidation.validation_other_requirements')->get()
        );

        // $projects = Project::whereHas('latestValidation.validation_other_requirements', function ($query) {
        //     $query->where('requirement_type', 'pamb');
        // })->get();

        // $projects = ProjectResource::collection($projects);

        return $projects;

        // $template = new TemplateProcessor(storage_path('app/templates/projects.docx'));

        // $template->setValue('date', now()->toDateString());
        // $template->cloneRow('name', count($projects));

        // foreach ($projects as $index => $project) {
        //     $i = $index + 1;
        //     $template->setValue("name#{$i}", $project['title']);
        //     $locations = $project->locations->map(function ($loc) {
        //         return "{$loc->sitio}, {$loc->barangay->barangay}, {$loc->barangay->municipality->municipality}";
        //     })->implode("\n");
        //     $template->setValue("location#{$i}", str_replace("\n", '<w:br/>', $locations));
        //     $template->setValue("cost#{$i}", $project['cost']);
        //     $template->setValue("status#{$i}", $project['status']);
        // }

        // $tempFile = tempnam(sys_get_temp_dir(), 'word');
        // $template->saveAs($tempFile);

        // return response()->download($tempFile, 'report.docx', [
        //     'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        // ])->deleteFileAfterSend(true);
    }

}
