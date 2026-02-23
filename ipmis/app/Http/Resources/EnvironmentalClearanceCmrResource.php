<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnvironmentalClearanceCmrResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'date_prepared' => $this->date_prepared,
            'date_submitted' => $this->date_submitted,
            'remarks' => $this->remarks,
            'file_path' => $this->file ? asset('storage/' . $this->file) : null
        ];
    }
}
