<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->user()->isCustomer();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => [
                'nullable',
                'string',
                Rule::unique(Category::class)->ignoreModel($this->routeModel('category', Category::class))
            ],
            'name' => ['nullable', 'string', 'max:255'],
            'picture' => ['nullable', 'file', 'image'],
        ];
    }
}
