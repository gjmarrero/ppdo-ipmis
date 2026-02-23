<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FundedProjectCreateRequest extends FormRequest
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
            'project_id' => ['nullable'],
            'title' => ['required_without:project_id', 'max:255', 'string'],
            'status' => ['required'],
            'approved_cost' => ['required', 'decimal:2'],
            'fundsource_id' => ['required', 'uuid'],
            'number' => ['required', 'max:255'],
            'has_supplemental_budget' => ['boolean'],
            'reference_number' => ['required', 'max:255'],
            'sb_number' => ['nullable', 'string'],
            'is_admin' => ['boolean'],
            'year_funded' => ['required'],
            'input_type' => ['required', 'string'],
            'locations' => ['required'],
            'locations.*.barangay_id' => ['nullable'],
            'locations.*.municipality_id' => ['nullable'],
        ];
    }
}
