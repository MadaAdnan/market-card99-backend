<?php

namespace App\Http\Resources\Api;

use App\Enums\BillStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'id' => $this->id_bill,
            'price' => $this->price,
            'amount' => $this->amount,
            'status' => $this->status->status(),
            'product' => $this->product->name,
            'customer_note' => $this->customer_note,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->customer_name,
            'customer_username' => $this->customer_username,
            'customer_password' => $this->password,
            'created_at' => $this->created_at->diffForHumans(),
            'code'=>$this->data_id
        ];
    }
}
