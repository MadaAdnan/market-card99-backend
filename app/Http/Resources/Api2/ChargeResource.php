<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class ChargeResource extends JsonResource
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
            'status'=>$this->status,
            'value'=>$this->value,
            'bank'=>$this->bank?->name,
            'img'=>$this->getImage(),
            'created_at'=>$this->created_at->format('Y-m-d')
        ];
    }
}
