<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSelfOrderRequest extends FormRequest
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
        $order = $this->routeModel('order', Order::class);

        if ($order->status !== Order::UNPAID) {
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
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'numeric', Rule::exists(Product::class, 'id')],
            'products.*.quantity' => ['required', 'numeric', 'min:1'],
        ];
    }
}
