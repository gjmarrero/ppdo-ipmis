<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OdsuResource extends JsonResource
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
            'office_id' => $this->office_id,
            'office' => $this->office->office,
            'division_id' => $this->division_id,
            'division' => $this->division->division,
            'office_division' => trim(($this->office?->office ?? '') . ' - ' . ($this->division?->division ?? '')),
        ];
    }
}
