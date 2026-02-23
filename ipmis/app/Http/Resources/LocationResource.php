<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'sitio' => $this->sitio ?? '',
            'barangay_id' => $this->barangay_id ?? '',
            'barangay' => $this->barangay?->barangay ?? '',
            'municipality_id' => $this->municipality_id ?? '',
            'municipality' => $this->municipality->municipality ?? '',
            // 'municipality_id' => $this->barangay?->municipality?->id,
            // 'municipality' => $this->barangay?->municipality?->municipality,
        ];
    }
}
