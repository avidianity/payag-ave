<?php

namespace App\Http\Requests;

use App\Helpers\Regex;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'regex:' . Regex::letters()],
            'email' => [
                'nullable',
                'email',
                Rule::unique(User::class)->ignoreModel($this->routeModel('user', User::class))
            ],
            'password' => ['nullable', 'string'],
            'phone' => ['nullable', 'regex:' . Regex::phMobileNumber()],
            'status' => ['nullable', 'boolean'],
            'picture' => ['nullable', 'file', 'image'],
        ];
    }
}
