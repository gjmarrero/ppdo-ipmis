<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImplementationInspectionResource extends JsonResource
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
            'date_request_received' => $this->date_request_received,
            'date_pre_inspection' => $this->date_pre_inspection,
            'date_post_inspection' => $this->date_post_inspection,
            'date_final_inspection' => $this->date_final_inspection,
            'date_report_prepared' => $this->date_report_prepared,
            'date_file_compilation_prepared' => $this->date_file_compilation_prepared,
            'remarks' => $this->remarks,
            'files' => ImplementationInspectionFileResource::collection($this->whenLoaded('files')),  
        ];
    }
}
