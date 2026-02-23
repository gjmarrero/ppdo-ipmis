<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantillaResource extends JsonResource
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
            'odsu' => $this->position->odsu_id,
            'position_id' => $this->position_id,
            'position_title' => $this->position->title,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee->employee_full_name
        ];
    }
}
