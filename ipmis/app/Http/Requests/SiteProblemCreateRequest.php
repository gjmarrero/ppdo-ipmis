<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteProblemCreateRequest extends FormRequest
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
            'funded_project_id' => "uuid|required",
            'problem_type' => "string|required",
            'date_report_prepared' => "date|required",
            'date_report_checked' => "date|nullable",
            'date_report_submitted' => "date|nullable",
            'remarks' => "string|nullable"
        ];
    }
}
