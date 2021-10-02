<?php

namespace App\Http\Resources;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'status' => $this->status,
            'role' => $this->role,
            'phone' => $this->phone,
            'customer_orders' => OrderResource::collection($this->whenLoaded('ordersAsCustomer')),
            $this->mergeWhen($this->resource->isAdmin(), [
                'biller_orders' => OrderResource::collection($this->whenLoaded('ordersAsBiller')),
            ]),
            'picture' => new FileResource($this->whenLoaded('picture')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
