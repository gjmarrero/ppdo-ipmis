<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImplementationOrderCreateRequest extends FormRequest
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
            'order_type' => 'required|string',
            'dts_barcode' => 'nullable',
            'date_prepared' => 'nullable|date|before_or_equal:today',
            'date_checked' => 'nullable|date|after_or_equal:date_prepared|before_or_equal:today',
            'date_reviewed' => 'nullable|date|after_or_equal:date_checked|before_or_equal:today',
            'date_recommended_for_approval' => 'nullable|date|after_or_equal:date_reviewed|before_or_equal:today',
            'date_forwarded_to_lce' => 'nullable|date|after_or_equal:date_recommended_for_approval|before_or_equal:today',
            'date_approved_by_lce' => 'nullable|date|after_or_equal:date_forwarded_to_lce|before_or_equal:today',
            'date_signed_by_contractor' => 'nullable|date|after_or_equal:date_approved_by_lce|before_or_equal:today',
        ];
    }
}
