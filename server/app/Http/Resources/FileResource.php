<?php

namespace App\Http\Resources;

class FileResource extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'size' => $this->size,
            'url' => $this->uri,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
