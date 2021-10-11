<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isStaff();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ['nullable', 'numeric', Rule::exists(Product::class, 'id')],
            'from' => ['nullable', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric'],
            'cost' => ['nullable', 'numeric'],
            'paid' => ['nullable', 'numeric'],
        ];
    }
}
