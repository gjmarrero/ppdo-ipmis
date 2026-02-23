<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnvironmentalClearanceCmrRequest extends FormRequest
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
            'environmental_clearance_id' => 'required|uuid',
            'date_prepared' => 'nullable|date',
            'date_submitted' => 'nullable|',
            'file' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'remarks' => 'nullable'
        ];
    }
}
