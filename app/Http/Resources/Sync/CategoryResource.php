<?php

namespace App\Http\Resources\Sync;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'is_available'=> $this->is_available && $this->active,
            'categories'=>$this->products->count()>0?[]: self::collection($this->categories),
            'products'=>$this->categories->count()>0?[]:ProductResource::collection($this->products),
            'is_check'=>(bool)$this->can_check,
        ];
    }
}
