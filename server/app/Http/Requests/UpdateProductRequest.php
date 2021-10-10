<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
                Rule::unique(Product::class)->ignoreModel($this->routeModel('product', Product::class))
            ],
            'description' => ['nullable', 'string'],
            'name' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'cost' => ['nullable', 'numeric'],
            'quantity' => ['nullable', 'numeric'],
            'category_id' => ['nullable', 'numeric', Rule::exists(Category::class, 'id')],
            'picture' => ['nullable', 'file', 'image'],
        ];
    }
}
