<?php

namespace App\Http\Resources;

use App\Models\ApprovedPow;
use App\Models\PreengineeringDocument;
use App\Models\PreengineeringScopeOfWork;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreengineeringStatusResource extends JsonResource
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
            'date_conducted' => $this->date_conducted,
            'programmed_cost' => $this->programmed_cost,
            'employee_id' => $this->employee?->id,
            'employee' => $this->employee?->first_name . ' ' . $this->employee?->last_name,
            'indicator' => $this->indicator,
            'project_type_id' => $this->project_type_id ?? 'No data',
            'project_type' => $this->projectType->project_type ?? 'No data',
            'date_prepared_pow' => $this->date_prepared_pow,
            'date_checked_pow' => $this->date_checked_pow,
            'date_reviewed_pow' => $this->date_reviewed_pow,
            'date_received_by_qc' => $this->date_received_by_qc,
            'employee_id_qcp' => $this->employee_id_qcp,
            'employee_qc' => $this->employee_qcp?->first_name . ' ' . $this->employee_qcp?->last_name,
            'date_qcp_prepared' => $this->date_qcp_prepared,
            'date_qcp_reviewed' => $this->date_qcp_reviewed,
            'date_submitted_divhead' => $this->date_submitted_divhead,
            'date_approved_pe' => $this->date_approved_pe,
            'date_submitted_lce' => $this->date_submitted_lce,
            'date_approved_lce' => $this->date_approved_lce,
            'date_received_by_ape' => $this->date_received_by_ape,
            'date_reviewed' => $this->date_reviewed,
            'date_recommended_for_approval' => $this->date_recommended_for_approval,
            'date_submitted_to_lce' => $this->date_submitted_to_lce,
            'latest_activity' => $this->latestActivity->activity ?? 'Ongoing POW/QCP preparation',
            'images' => PreengineeringImageResource::collection(
                $this->preengineering_images ?? collect()
            ),
            'documents' => PreengineeringDocumentResource::collection(
                $this->preengineering_documents ?? collect()
            ),
            'scopes' => PreengineeringScopeOfWorkResource::collection(
                        $this->scopes ?? collect()
            ),
            'approved_pow' => $this->approved_pow?->file_link,
            'preengineering_status' => $this->date_conducted ? 'With Pre-engineering' : 'For Pre-engineering',
            'qcp_status' => $this->qcp_status ?? 'Pending',
            'review_status' => $this->review_status ?? 'Pending',
        ];
    }
}
