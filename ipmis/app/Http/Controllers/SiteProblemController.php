<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteProblemCreateRequest;
use App\Http\Resources\SiteProblemResource;
use App\Models\FundedProject;
use App\Models\Project;
use App\Models\SiteProblem;
use App\Models\SiteProblemFile;
use Illuminate\Http\Request;

class SiteProblemController extends Controller
{
    public function store(SiteProblemCreateRequest $request)
    {
        $data = $request->validated();

        $site_problem = SiteProblem::create($data);

        return new SiteProblemResource($site_problem);
    }

    public function pdc_result_store(Request $request)
    {
        $siteProblem = SiteProblem::findOrFail($request->site_problem_id);
        $siteProblem->update([
            'pdc_result' => $request->pdc_result ?? null,
            'archive_id' => $request->archive_id ?? null, 
            'new_project_title' => $request->new_project_title ?? null,
            'additional_fund' => $request->additional_fund ?? null,
            'fundsource_id' => $request->fundsource_id ?? null,
        ]);
        // if ($request->hasFile('files')) {
        //     foreach ($request->file('files') as $index => $file) {
        //         $filePath = $file->store('project_supporting_documents', 'public');
        //         SiteProblemFile::create([
        //             'site_problem_id' => $siteProblem->id,
        //             'file' => $filePath,
        //             'file_type' => $request->file_types[$index] ?? null,
        //             'remarks' => $request->remarks ?? null,
        //         ]);
        //     }
        // }
        $funded_project = FundedProject::findOrFail($siteProblem->funded_project_id);
        $project = Project::findOrFail($funded_project->project_id);
        $project->update([
            'latest_title' => $request->new_project_title ?? null,
        ]);
    }
}
