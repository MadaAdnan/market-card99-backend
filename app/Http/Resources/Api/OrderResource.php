<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'id'=>$this->id,
            'phone'=>$this->phone,
            'code'=>$this->code,
            'country'=>$this->country->name,
            'app'=>$this->program->name,
            'img'=>$this->program->getImage(),
            'price'=>$this->price,
            'status'=>$this->status
        ];
    }
}
