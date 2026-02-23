<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FundedProjectResource extends JsonResource
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
            'number' => $this->number,
            'reference_number' => $this->reference_number,
            'title' => $this->project->latest_title ?? $this->project->title,
            'initial_title' => $this->project->title,
            'latest_title' => $this->project->latest_title,
            'fundsource_id' => $this->fundsource->id,
            'fundsource' => $this->fundsource->fundsource,
            'approved_cost' => $this->approved_cost,
            'has_supplemental_budget' => $this->has_supplemental_budget,
            'sb_number' => $this->sb_number,
            'is_admin' => $this->is_admin,
            'year_funded' => $this->year_funded,
            'project' => new ProjectResource($this->project),
            'latest_preengineering' => $this->latestPreengineering,
            'preengineering' => PreengineeringStatusResource::collection($this->whenLoaded('preengineering_statuses')),
            'preengineering_status' => $this->whenLoaded('preengineering_statuses', function () {
                return $this->preengineering_statuses->isNotEmpty()
                    ? 'With pre-engineering'
                    : 'For pre-engineering';
            }),
            'site_problem' => SiteProblemResource::collection($this->whenLoaded('site_problems')),
            'latest_site_problem' => $this->latestSiteProblem,
            'ec_status' => $this->whenLoaded('environmental_clearances', function () {
                if ($this->environmental_clearances->isEmpty()) {
                    return 'For Application';
                }

                $clearance = $this->environmental_clearances->first();

                if ($clearance->date_issued) {
                    return 'Clearance Issued';
                }

                if ($clearance->date_application && !$clearance->date_issued) {
                    return 'On Process';
                }

                return 'For Application';
            }),

            'environmental_clearance' => EnvironmentalClearanceResource::collection($this->whenLoaded('environmental_clearances')),
            'implementation' => ImplementationResource::collection($this->whenLoaded('implementations')),
            'document_status' => $this->implementations->isNotEmpty()
                ? 'Received'
                : 'Pending',
            'created_by' => $this->user_id,
            'created_at' => $this->created_at,
        ];
    }
}
