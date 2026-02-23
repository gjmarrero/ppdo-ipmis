<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectOtherRequirementCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectOtherRequirementController extends Controller
{
    public function store(ProjectOtherRequirementCreateRequest $request)
    {
        $data = $request->validated();
        $project = '1';
        if (!empty($data['selectedRequirementTypes']) && is_iterable($data['selectedRequirementTypes'])) {
            foreach ($data['selectedRequirementTypes'] as $requirementId) {
                $project->otherRequirements()->create([
                    'requirement_type' => $requirementId,
                    'pamb_type_id' => $requirementId === 'pamb_clearance' ? ($data['pamb_type'] ?? null) : null,
                    'user_id' => Auth::id()
                ]);
            }
        }
    }
}
