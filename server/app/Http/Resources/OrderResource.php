<?php

namespace App\Http\Resources;

use App\Models\OrderProduct;

class OrderResource extends JsonResource
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
            'paid' => $this->paid,
            'status' => $this->status,
            'customer' => $this->whenLoaded('customer'),
            'biller' => $this->whenLoaded('biller'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
