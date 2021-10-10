<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return OrderResource::collection(Order::with([
            'items.product', 'customer', 'biller'
        ])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();

        $data['biller_id'] = $request->user()->id;

        $order = Order::create($data);

        $items = Product::findMany(collect($data['products'])->map->id)
            ->map(function (Product $product, $index) use ($data) {
                $quantity = $data['products'][$index]['quantity'];
                return new OrderItem(['product_id' => $product->id, 'quantity' => $quantity]);
            });

        $order->items()->saveMany($items);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $order->load([
            'items.product', 'customer', 'biller',
        ]);

        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $order->update($data);

        $order->items->each->delete();

        $items = Product::findMany(collect($data['products'])->map->id)
            ->map(function (Product $product, $index) use ($data) {
                $quantity = $data['products'][$index]['quantity'];
                return new OrderItem(['product_id' => $product->id, 'quantity' => $quantity]);
            });

        $order->items()->saveMany($items);

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if ($order->status !== Order::UNPAID) {
            throw (new ModelNotFoundException)->setModel(
                $order,
                $order->id,
            );
        }

        $order->delete();

        return response('', 204);
    }
}
