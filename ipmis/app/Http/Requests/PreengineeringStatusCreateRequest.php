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
            'date_conducted' => 'required|date|before_or_equal:today',
            'programmed_cost' => 'required',
            'project_type_id' => 'required',
            'scopes' => 'required',
            'scopes.*.scope_of_work_id' => 'required',
            'scopes.*.description' => 'required',
            'date_prepared_pow' => 'nullable|date|after_or_equal:date_conducted|before_or_equal:today',
            'date_checked_pow' => 'nullable|date|after_or_equal:date_prepared_pow|before_or_equal:today',
            'date_reviewed_pow' => 'nullable|date|after_or_equal:date_checked_pow|before_or_equal:today',
            'date_approved_lce' => 'nullable|date|after_or_equal:date_submitted_lce|before_or_equal:today',
        ];
    }
}
