<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FundedProjectDedicatedResource extends JsonResource
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
            'fundsource' => $this->fundsource->fundsource ?? '',
            'fundsource_id' => $this->fundsource_id ?? '',
            'approved_cost' => $this->approved_cost,
            'has_supplemental_budget' => $this->has_supplemental_budget,
            'sb_number' => $this->sb_number,
            'year_funded' => $this->year_funded,
            'is_admin' => $this->is_admin,
            'remarks' => $this->remarks,
            'latest_preengineering' => $this->when(
                $this->relationLoaded('latestPreengineering'),
                fn() => new PreengineeringStatusResource($this->latestPreengineering)
            ),
            'preengineerings' => $this->whenLoaded(
                'preengineering_statuses',
                fn() => PreengineeringStatusResource::collection($this->preengineering_statuses)
            ),

            'preengineering_status' => $this->whenLoaded('preengineering_statuses', function () {
                if ($this->preengineering_statuses->isNotEmpty()) {
                    return 'With pre-engineering';
                }

                return 'For pre-engineering';
            }),
            'latest_site_problem' => $this->when(
                $this->relationLoaded('latestSiteProblem'),
                fn() => new SiteProblemResource($this->latestSiteProblem)
            ),
            'latest_environmental_clearance' => $this->when(
                $this->relationLoaded('latestEnvironmentalClearance'),
                fn() => new EnvironmentalClearanceResource($this->latestEnvironmentalClearance)
            ),
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
            'latest_implementation' => $this->when(
                $this->relationLoaded('latestImplementation'),
                fn() => new ImplementationResource($this->latestImplementation)
            ),
        ];
    }
}
