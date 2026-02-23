<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCompleteResource extends JsonResource
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
            'title' => $this->latest_title ?? $this->title,
            'initial_title' => $this->title,
            'latest_title' => $this->latest_title,
            'status' => $this->status,
            'cost' => $this->cost,
            'fundsource' => $this->fundsource ?? '',
            'project_type_array' => $this->project_type,
            'project_type_id' => optional($this->project_type)->id ?? '',
            'project_type' => optional($this->project_type)->project_type ?? '',
            'input_type' => $this->input_type,
            'locations' => LocationResource::collection($this->locations),
            'supporting_documents' => ProjectSupportingDocumentResource::collection($this->documents),
            'project_other_requirements' => ProjectOtherRequirementResource::collection($this->otherRequirements),
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
            'validation_assignment' => optional($this->latestValidation)->date_assigned ? 'Assigned' : 'Unassigned',
            'validation_status' => optional($this->latestValidation)->date_validation_report ? 'Validated' : 'Unvalidated',
            'latest_funding' => new FundedProjectDedicatedResource($this->whenLoaded('latestFunding')),
            // 'other_requirements' => new ProjectOtherRequirementResource($this->whenLoaded('otherRequirements')),
            'created_by' => $this->user->name,
            'created_at' => $this->created_at,
        ];
    }
}
