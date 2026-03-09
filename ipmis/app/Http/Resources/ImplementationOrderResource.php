<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImplementationOrderResource extends JsonResource
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
            'order_type' => $this->order_type,
            'dts_barcode' => $this->dts_barcode,
            'date_prepared' => $this->date_prepared,
            'date_checked' => $this->date_checked,
            'date_reviewed' => $this->date_reviewed,
            'date_recommended_for_approval' => $this->date_recommended_for_approval,
            'date_forwarded_to_lce' => $this->date_forwarded_to_lce,
            'date_approved_by_lce' => $this->date_approved_by_lce,
            'date_signed_by_contractor' => $this->date_signed_by_contractor,
            'files' => ImplementationOrderDocumentResource::collection($this->whenLoaded('files')),
        ];
    }
}
