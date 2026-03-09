<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImplementationResource extends JsonResource
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
            'date_received' => $this->date_received,
            'employee_id' => $this->employee_id,
            'employee' => $this->employee->last_name.', '.$this->employee->first_name,
            'date_start' => $this->date_start,
            'user_id' => $this->user->name,
            'implementation_document_status' => $this->implementation_document_status,
            'monthly_schedule_accomplishment' => $this->implementation_monthly_accomplishments,
            'implementation_orders' => $this->orders,
            'implementation_inspections' => $this->inspections,
        ];
    }
}
