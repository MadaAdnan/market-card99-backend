<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'img'=>$this->getImage(),
            'country_img'=>$this->country?->getImage(),
            'name'=>$this->name,
            'price'=>(double)number_format($this->getTotalPrice(),2)
        ];
    }
}
