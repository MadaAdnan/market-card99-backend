<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'=>$this->id,
            'name'=>$this->name,
            'img'=>$this->getImage(),
            'is_free'=>$this->is_free,
            'min_amount'=>$this->min_amount,
            'max_amount'=>$this->max_amount,
            'is_available'=>$this->is_available,
            'is_discount'=>$this->is_discount,
            'price'=>$this->getPrice()
        ];
    }
}
