<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreengineeringActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'activity' => $this->activity,
            'employee' => $this->employee->first_name,
            'remarks' => $this->remarks,
            'date_updated' => $this->created_at
        ];
    }
}
