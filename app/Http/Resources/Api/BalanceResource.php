<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
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
            'id'=>$this->id,
            'credit' => $this->credit,
            'debit' => $this->debit,
            'total' => $this->total,
            'info' => $this->info,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
