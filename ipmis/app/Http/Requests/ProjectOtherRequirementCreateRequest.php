<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectOtherRequirementCreateRequest extends FormRequest
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
