<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'numeric', Rule::exists(User::class, 'id')],
            'paid' => ['required', 'numeric'],
            'status' => ['required', 'string', Rule::in(Order::STATUSES)],
            'products' => ['required', 'array:id', 'min:1'],
            'products.*.id' => ['required', 'numeric', Rule::exists(Product::class, 'id')],
        ];
    }
}
