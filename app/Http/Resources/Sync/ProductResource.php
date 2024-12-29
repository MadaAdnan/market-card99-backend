<?php

namespace App\Http\Resources\Sync;

use App\Models\Item;
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
        $price=$this->getUnitPrice();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_free' => (bool)$this->is_free,
            'min_qty' => $this->min_amount,
            'max_qty' => $this->max_amount,
            'count' => $this->count,
            'is_available' => $this->getStatus() && $this->active,
            'is_active' => $this->active,
            'price' => $this->is_free && $this->amount>0?($price*$this->min_amount):$this->getPrice(),
            'unit_price' => $price,
            'info'=>$this->info,
            'category_id'=>$this->category_id,
        ];
    }
    public function getUnitPrice(){
        return $this->getPrice() / ($this->amount > 0 ? $this->amount : 1);
    }
    public function getStatus()
    {
        if ($this->type->value == 'default' && !$this->is_active_api) {
            $count=Item::where(['product_id' => $this->id, 'active' => 1])->count();
            if ( !$count) {
                return false;
            }
        }
        return (bool)$this->is_available;

    }
}
