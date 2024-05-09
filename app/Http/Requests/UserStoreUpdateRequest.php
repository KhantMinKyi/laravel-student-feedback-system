<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreUpdateRequest extends FormRequest
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
            'name' => 'required|string',
            'username' => ['required', 'string', Rule::unique('users', 'username')->ignore($this->id, 'id')],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->id, 'id')],
            'password' => 'nullable|string',
            'dob' => 'required|date',
            'uni_registration_no' => 'nullable|string',
            'is_hod' => 'nullable|boolean',
            'address' => 'required|string',
            'father_name' => 'required|string',
            'nrc' => 'required|string',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'type' => 'required|string',
        ];
    }
}
