<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreengineeringScopeOfWorkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'scope_of_work_id' => $this->scope_of_work_id,
            'scope_of_work' => $this->specific_scope_of_work->scope,
            'description' => $this->description,
        ];
    }
}
