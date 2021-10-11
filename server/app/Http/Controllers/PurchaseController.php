<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PurchaseResource::collection(Purchase::with('user', 'product')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePurchaseRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        return new PurchaseResource(Purchase::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('user', 'product');
        return new PurchaseResource($purchase);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePurchaseRequest $request
     * @param  \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        $purchase->update($data);

        return new PurchaseResource($purchase);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return response('', 204);
    }
}
