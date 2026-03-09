<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Foundation\Http\FormRequest;
use Str;

class UserCreateRequest extends FormRequest
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
        $table = (new User)->getTable();
        $currentId = $this->route('user') ?? $this->user()?->id;

        if ($currentId) {
            $emailRule = ['required', 'string', 'email', 'max:255', Rule::unique($table)->ignore($currentId)];
        } else {
            $emailRule = ['required', 'string', 'email', 'max:255', Rule::unique($table)];
        }

        return [
            'employee_id' => ['required', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRule,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ];
    }


}
