<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FundsourceResource extends JsonResource
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
            'fundsource' => $this->fundsource,
            'fundsource_acc' => $this->fundsource_acc,
            'fundsource_abbrev' => $this->fundsource_abbrev,
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
        ];
    }
}
