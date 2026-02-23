<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreengineeringSiteProblemCreateRequest extends FormRequest
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
            'site_problem' => 'required|string',
            'date_prepared_report' => 'nullable|date',
            'date_approved_pe_report' => 'nullable|date',
            'date_submitted_lce_report' => 'nullable|date',
        ];
    }
}
