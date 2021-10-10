<?php

namespace App\Http\Resources;

class ProductResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'cost' => $this->cost,
            'quantity' => $this->quantity,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'picture' => new FileResource($this->whenLoaded('picture')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
