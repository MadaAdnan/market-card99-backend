<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
            'credit'=>$this->credit,
            'debit'=>$this->debit,
            'info'=>$this->info,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'total'=>$this->total
        ];
    }
}
