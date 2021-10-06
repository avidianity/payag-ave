<?php

namespace App\Http\Requests;

use App\Helpers\Regex;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'password' => ['required', 'string', 'confirmed'],
            'phone' => ['required', 'regex:' . Regex::phMobileNumber()],
        ];
    }
}
