<?php

namespace App\Http\Resources\Api2;

use App\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'info' => $this->info,
            'img' => $this->getImage(),
            'is_free' => (bool)$this->is_free,
            'min_qty' => $this->min_amount,
            'max_qty' => $this->max_amount,
            'count' => $this->count,
            'is_available' => $this->getStatus(),
            'is_discount' => (bool)$this->is_discount,
            'price' => $this->getPrice(),
            'category_name' => $this->category?->name,
            'unit_price' => $this->getPrice() / ($this->amount > 0 ? $this->amount : 1),
            'can_check' => (bool)$this->category?->can_check,
            'is_url' => (bool)$this->is_url,
            'category_id'=>$this->category_id,

        ];
    }

    public function getStatus()
    {
        if ($this->type->value == 'default' && !$this->is_active_api) {
            $count = Item::where(['product_id' => $this->id, 'active' => 1])->count();
            if (!$count && !$this->force_available) {
                return false;
            }
        }
        return (bool)$this->is_available || $this->force_available ;

    }


}
