<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidationCreateRequest extends FormRequest
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
            'project_id' => [
                'required',
                'uuid',
                Rule::exists('projects', 'id')
            ],
            'project_type_id' => 'uuid|nullable',
            'date_assigned' => 'date|nullable',
            'date_validated' => 'nullable|date|after:date_assigned',
            'date_validation_report' => 'nullable|date|after:date_assigned',
            'actions_recommendations' => 'nullable|string',
            'present_during_validation' => 'nullable|string',
            'remarks' => 'nullable|string',
            'images' => 'nullable',
            'images.*.file' => 'required_with:images',
            'images.*.lat' => 'required_with:images',
            'images.*.long' => 'required_with:images',
            'files' => 'nullable',
            'files.*' => 'file',
            'mimes:pdf,jpg,png,docx',
            'max:2048',
            'responsible_persons' => 'nullable',
            'responsible_persons.*.employee_id' => 'nullable',
            'mapImages.*' => 'nullable',
            'selectedRequirementTypes.*' => 'nullable',

            'pamb_type' => [
                'nullable',
                Rule::requiredIf(
                    fn() =>
                    in_array('pamb_clearance', $this->input('selectedRequirementTypes', []))
                ),
                'exists:e_c_pamb_types,id',
            ],

        ];
    }
}
