<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectOtherRequirementResource extends JsonResource
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
            'project_id' => $this->project_id,
            'requirement_type' => $this->requirement_type,
            'date_issued' => $this->date_issued,
            'date_applied' => $this->date_applied,
            'pamb_type_id' => $this->pamb_type_id ?? '',
            'pamb_area' => $this->pamb_area->type ?? '',
            'status' => $this->status,
            'employee_id' => $this->employee_id,
            'documents' => otherRequirementDocumentResource::collection($this->documents),
        ];
    }
}
