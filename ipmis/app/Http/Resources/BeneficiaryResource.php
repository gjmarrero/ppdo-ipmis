<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'beneficiary_type' => $this->beneficiary_type->beneficiary_type,
            'beneficiary' => $this->beneficiary,
            'beneficiary_sdd' => BeneficiarySddResource::collection(
                optional($this->whenLoaded('beneficiary_sdds')) ?? collect()
            ),
        ];
    }
    
}
