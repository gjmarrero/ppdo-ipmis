<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreengineeringSiteProblemResource extends JsonResource
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
            'site_problem' => $this->site_problem,
            'date_prepared_report' => $this->date_prepared_report,
            'date_approved_pe_report' => $this->date_approved_pe_report,
            'date_submitted_lce_report' => $this->date_submitted_lce_report
        ];
    }
}
