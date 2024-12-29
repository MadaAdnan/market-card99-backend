<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
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
            'face'=>$this->face,
//            'instagram'=>$this->instagram,
//            'telegram'=>$this->telegram,
            'whats'=>$this->whats,
        ];
    }
}
