<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /**
         * @var \App\Models\Order
         */
        $order = $this->route('order');

        /**
         * @var \App\Models\User
         */
        $user = $this->user();

        if ($order->status === Order::PAID && $user->isCustomer()) {
            return false;
        }

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
            'customer_id' => ['nullable', 'numeric', Rule::exists(User::class, 'id')],
            'paid' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string', Rule::in(Order::STATUSES)],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'numeric', Rule::exists(Product::class, 'id')],
        ];
    }
}
