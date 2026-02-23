<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectProposalFundedSummaryResource extends JsonResource
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
            'locations' => LocationResource::collection($this->locations),
            'validation' => [
                'data' => $this->latestValidation,
                'responsible_people' => ValidationResponsiblePeopleResource::collection(
                    optional($this->latestValidation)->relationLoaded('validation_responsible_people')
                    ? $this->latestValidation->validation_responsible_people
                    : collect()
                ),
                'beneficiaries' => BeneficiaryResource::collection(
                    optional($this->latestValidation)->relationLoaded('beneficiaries')
                    ? $this->latestValidation->beneficiaries
                    : collect()
                ),
                'images' => ValidationImageResource::collection(
                    optional($this->latestValidation)->relationLoaded('validation_images')
                    ? $this->latestValidation->validation_images
                    : collect()
                ),
                'map_images' => ValidationMapImageResource::collection(
                    optional($this->latestValidation)->relationLoaded('validation_map_images')
                    ? $this->latestValidation->validation_map_images
                    : collect()
                ),
                'files' => ValidationSupportingDocumentResource::collection(
                    optional($this->latestValidation)->relationLoaded('validation_supporting_documents')
                    ? $this->latestValidation->validation_supporting_documents
                    : collect()
                ),
                'other_requirements' => ValidationOtherRequirementResource::collection(
                    optional($this->latestValidation)->relationLoaded('validation_other_requirements')
                    ? $this->latestValidation->validation_other_requirements
                    : collect()
                )
            ],
            'title' => $this->latest_title ?? $this->title,
            'initial_title' => $this->title,
            'latest_title' => $this->latest_title,
            'status' => $this->status,
            'cost' => $this->cost,
            'fundsource' => $this->fundsource,
            'supporting_documents' => ProjectSupportingDocumentResource::collection($this->documents),
            'project_type_array' => $this->project_type,
            'project_type_id' => optional($this->project_type)->id ?? '',
            'project_type' => optional($this->project_type)->project_type ?? '',
            'validation_assignment' => optional($this->latestValidation)->date_assigned ? 'Assigned' : 'Unassigned',
            'validation_status' => optional($this->latestValidation)->date_validation_report ? 'Validated' : 'Unvalidated',
            'input_type' => $this->input_type,
            'latest_funding' => $this->whenLoaded('latestFunding', function () {
                $funding = $this->latestFunding;

                return $funding ? [
                    'id' => $funding->id,
                    'approved_cost' => $funding->approved_cost,
                    'number' => $funding->number,
                    'reference_number' => $funding->reference_number,
                    'fundsource' => $funding->fundsource ? [
                        'id' => $funding->fundsource->id,
                        'name' => $funding->fundsource->fundsource,
                    ] : null,
                ] : null;
            }),
            'created_by' => $this->user->name,
            'created_at' => $this->created_at,
        ];
    }
}
