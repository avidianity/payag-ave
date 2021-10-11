<?php

namespace App\Http\Resources;

class PurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'user' => new UserResource($this->whenLoaded('user')),
            'from' => $this->from,
            'amount' => $this->amount,
            'cost' => $this->cost,
            'paid' => $this->paid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
