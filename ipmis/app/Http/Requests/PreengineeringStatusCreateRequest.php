<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreengineeringStatusCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'funded_project_id' => 'required|uuid|exists:funded_projects,id',
            'employee_id' => 'required|uuid|exists:employees,id',
            'date_conducted' => 'required|date',
            'programmed_cost' => 'required',
            'project_type_id' => 'required',
            'scopes' => ['required'],
            'scopes.*.scope_of_work_id' => ['required'],
            'scopes.*.description' => ['required'],
            'date_prepared_pow' => 'nullable|date',
            'date_checked_pow' => 'nullable|date',
            'date_reviewed_pow' => 'nullable|date',
            'date_received_by_qc' => 'nullable|date',
            'employee_id_qcp' => 'nullable',
            'date_qcp_prepared' => 'nullable|date',
            'date_qcp_reviewed' => 'nullable|date',
            'date_approved_pe' => 'nullable',
            'date_submitted_lce' => 'nullable',
            'date_submitted_divhead' => 'nullable',
            'date_approved_lce' => 'nullable'
        ];
    }
}
