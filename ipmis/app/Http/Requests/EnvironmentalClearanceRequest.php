<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnvironmentalClearanceRequest extends FormRequest
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
            'e_c_certificate_type_id' => 'required',
            'e_c_pamb_type_id' => 'nullable',
            'date_application' => 'required|date|before_or_equal:today',
            'date_issued' => 'nullable|date|after_or_equal:date_application|before_or_equal:today',
            'employee_id' => 'required|uuid|exists:employees,id',
            'status' => 'required',
            'is_transmitted_peo' => 'boolean',
            'date_transmitted' => 'nullable|date|after_or_equal:date_issued|before_or_equal:today',
            'pamb_date_applied' => 'nullable|date',
            'pamb_date_issued' => 'nullable|date',
            'pamb_status' => 'nullable|string',
            'cmr_date_prepared' => 'nullable|date|before_or_equal:today',
            'cmr_date_submitted' => 'nullable|date|before_or_equal:today',
            'cmr_file' => 'nullable|file|mimes:pdf,jpg,png,docx|max:2048',
            'remarks' => 'nullable'
        ];
    }
}
