<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSelfOrderRequest;
use App\Http\Requests\UpdateSelfOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class SelfOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return OrderResource::collection(
            $request->user()
                ->ordersAsCustomer()
                ->with('products', 'biller')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSelfOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSelfOrderRequest $request)
    {
        $data = $request->validated();

        $data['paid'] = 0;
        $data['customer_id'] = $request->user()->id;
        $data['status'] = Order::UNPAID;

        $products = Product::findMany(collect($data['products'])->map->id);

        $order = Order::create($data);

        $order->products()->attach($products);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return new OrderResource(
            $request->user()
                ->ordersAsCustomer()
                ->with('products', 'customer', 'biller')
                ->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSelfOrderRequest $request
     * @param  int $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSelfOrderRequest $request, $id)
    {
        $data = $request->validated();

        /**
         * @var \App\Models\Order
         */
        $order = $request->user()
            ->ordersAsCustomer()
            ->with('products', 'customer', 'biller')
            ->findOrFail($id);

        $products = Product::findMany(collect($data['products'])->map->id);

        $order->update($data);

        $order->products()->sync($products);

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        /**
         * @var \App\Models\Order
         */
        $order = $request->user()
            ->ordersAsCustomer()
            ->with('products', 'customer', 'biller')
            ->where('status', Order::UNPAID)
            ->findOrFail($id);

        $order->delete();

        return response('', 204);
    }
}
