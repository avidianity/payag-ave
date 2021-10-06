<?php

namespace App\Http\Requests;

use App\Helpers\Regex;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'regex:' . Regex::letters()],
            'email' => ['required', 'email', Rule::unique(User::class), 'regex:' . Regex::email()],
            'password' => ['required', 'string'],
            'phone' => ['required', 'regex:' . Regex::phMobileNumber()],
            'role' => ['required', 'string', Rule::in(User::ROLES)],
            'status' => ['required', 'boolean'],
            'picture' => ['nullable', 'file', 'image'],
        ];
    }
}
