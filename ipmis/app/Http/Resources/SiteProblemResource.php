<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteProblemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'problem_type' => $this->problem_type,
            'date_report_prepared' => $this->date_report_prepared,
            'date_report_checked' => $this->date_report_checked,
            'date_report_submitted' => $this->date_report_submitted,
            'new_project_tilte' => $this->new_project_title,
            'additional_fund' => $this->additional_fund,
            'fundsource' => $this->fundsource->fundsource ?? null,
            'supplemental_budget_number' => $this->archive,
            'pdc_result' => $this->pdc_result,
            'remarks' => $this->remarks,
            // 'files' => SiteProblemFileResource::collection($this->site_problem_files),
        ];
    }
}
