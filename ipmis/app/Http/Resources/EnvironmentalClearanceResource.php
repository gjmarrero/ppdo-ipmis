<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnvironmentalClearanceResource extends JsonResource
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
            'certificate_type_id' => $this->e_c_certificate_type_id,
            'certificate_type' => $this->certificate_type->type,
            'pamb_id' => $this->e_c_pamb_type_id,
            'pamb' => $this->pamb_type->type ?? '',
            'date_application' => $this->date_application,
            'date_issued' => $this->date_issued,
            'employee_id' => $this->employee_id,
            'employee' => $this->employee_in_charge->name ?? '',
            'status' => $this->status,
            'is_transmitted_peo' => $this->is_transmitted_peo,
            'date_transmitted' => $this->date_transmitted,
            'pamb_date_applied' => $this->pamb_date_applied,
            'pamb_date_issued' => $this->pamb_date_issued,
            'pamb_status' => $this->pamb_status,
            'cmr_date_prepared' => $this->cmr_date_prepared,
            'cmr_date_submitted' => $this->cmr_date_submitted,
            'cmr_file' => $this->cmr_file,
            'remarks' => $this->remarks,
            'environmental_clearance_status' => $this->environmental_clearance_status ?? 'No Clearance',
            'files' => EnvironmentalClearanceDocumentResource::collection($this->whenLoaded('environmental_clearance_files')),
            'cmrs' => EnvironmentalClearanceCmrResource::collection($this->whenLoaded('environmental_clearance_cmrs')),
        ];
    }
}
