<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'date_assigned' => $this->date_assigned,
            'date_validated' => $this->date_validated,
            'date_validation_report' => $this->date_validation_report,
            'actions_recommendations' => $this->actions_recommendations,
            'present_during_validation' => $this->present_during_validation,
            'remarks' => $this->remarks,
            'is_pamb' => $this->is_pamb,
            'created_by' => $this->user->name,
            'created_at' => $this->created_at,
        ];
    }
}
