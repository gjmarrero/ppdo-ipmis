<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectTypeResource extends JsonResource
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
            'name' => $this->project_type,
            'project_type_code' => $this->project_type_code,
            'beneficiary_types' => BeneficiaryTypeResource::collection($this->whenLoaded('beneficiaryTypes')),
        ];
    }
}
