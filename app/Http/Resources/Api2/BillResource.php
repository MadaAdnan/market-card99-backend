<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'id_bill'=>$this->id_bill,
            'product'=>new ProductResource($this->product),
            'price'=>$this->price,
            'status'=>$this->status,
            'amount'=>$this->amount,
            'cancel_note'=>$this->cancel_note,
            'customer_id'=>$this->customer_id,
            'customer_name'=>$this->customer_name,
            'customer_username'=>$this->customer_username,
            'customer_password'=>$this->customer_password,
            'created_at'=>$this->created_at->format('Y-m-d h:i a'),
            'data_id'=>$this->data_id
        ];
    }
}
