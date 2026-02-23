<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectCreateRequest extends FormRequest
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
            'title' => ['required', 'max:255', 'string'],
            'status' => ['required'],
            'fundsource_id' => ['uuid', 
                Rule::exists('fundsources','id'),
                'required_if:status,proposal', 
                ],
            'project_type_id' => ['uuid',
                Rule::exists('project_types','id'),
                'required_if:status,proposal',
                ],
            'cost' => ['required_if:status,proposal',],
            'files' => ['nullable'],
            'files.*' => ['file','mimes:pdf,jpg,png,docx','max:2048'],
            'locations' => ['required'],
            'locations.*.barangay_id' => ['nullable'],
            'locations.*.municipality_id' => ['nullable'],
            'input_type' => ['required','string'],
        ];
    }
}
