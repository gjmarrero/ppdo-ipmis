<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImplementationInspectionCreateRequest extends FormRequest
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
            'date_request_received' => 'required|date|before_or_equal:today',
            'date_pre_inspection' => 'nullable|date|after_or_equal:date_request',
            'date_post_inspection' => 'nullable|date|after_or_equal:date_pre_inspection',
            'date_final_inspection' => 'nullable|date|after_or_equal:date_post_inspection',
            'date_report_prepared' => 'nullable|date|after_or_equal:date_final_inspection',
            'date_file_compilation_prepared' => 'nullable|date|after_or_equal:date_report_prepared',
            'remarks' => 'nullable|string',
        ];
    }
}
