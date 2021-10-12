<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetUsersRequest extends FormRequest
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
            'role' => ['nullable', 'string', Rule::in(User::ROLES)],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['required', 'string', Rule::in(User::ROLES)],
        ];
    }
}
