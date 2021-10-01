<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'code' => ['required', 'string', Rule::unique(Product::class)],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'category_id' => ['required', 'numeric', Rule::exists(Category::class, 'id')],
        ];
    }
}
