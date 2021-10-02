<?php

namespace App\Http\Requests;

use App\Helpers\Regex;
use Illuminate\Foundation\Http\FormRequest;

class ResendVerificationMailRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', 'regex:' . Regex::email()],
            'password' => ['required', 'string', 'max:255'],
        ];
    }
}
