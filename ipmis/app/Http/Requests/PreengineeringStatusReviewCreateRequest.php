<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreengineeringStatusReviewCreateRequest extends FormRequest
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
            'date_received_by_ape' => 'nullable|date|before_or_equal:today',
            'date_reviewed' => 'nullable|date|after_or_equal:date_received_by_ape|before_or_equal:today',
            'date_recommended_for_approval' => 'nullable|date|after_or_equal:date_reviewed|before_or_equal:today',
            'date_submitted_to_lce' => 'nullable|date|after_or_equal:date_recommended_for_approval|before_or_equal:today',
        ];
    }
}
