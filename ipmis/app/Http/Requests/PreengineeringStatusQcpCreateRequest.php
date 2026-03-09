<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreengineeringStatusQcpCreateRequest extends FormRequest
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
            'date_received_by_qc' => 'nullable|date|before_or_equal:today',
            'date_qcp_prepared' => 'nullable|date|after_or_equal:date_received_by_qc|before_or_equal:today',
            'date_qcp_reviewed' => 'nullable|date|after_or_equal:date_qcp_prepared|before_or_equal:today',
            'employee_id_qcp' => 'nullable|uuid|exists:employees,id',
        ];
    }
}
