<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderNumberResource extends JsonResource
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
            'server'=>$this->server?->name,
            'country'=>$this->country?->name,
            'app'=>$this->program?->name,
            'phone'=>$this->phone,
            'price'=>$this->price,
            'status'=>$this->status,
            'code'=>$this->code,
            'created_at'=>$this->created_at->format('Y-m-d h:i a')
        ];
    }
}
