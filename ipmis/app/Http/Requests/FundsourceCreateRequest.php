<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundsourceCreateRequest extends FormRequest
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
            'fundsource' => 'required|max:255|string',
            'fundsource_code' => 'required|max:20|string',
            'fundsource_abbrev' => 'required|max:20|string'
        ];
    }
}
