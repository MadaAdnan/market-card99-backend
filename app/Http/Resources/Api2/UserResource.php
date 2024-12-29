<?php

namespace App\Http\Resources\Api2;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "username" => $this->username,
            "phone" => $this->phone,
            "address" => $this->address,
            "group" => $this->group ? new GroupResource($this->group) : null,
            'balance' => $this->getTotalBalance(),
            'point' => $this->getTotalPoint(),
            'total_bay' => $this->getTotalBay(),
            'buy_in_month' => $this->getTotalBayInMonth(),
            'api_key' => $this->token,
            'is_dashboard' => ($this->hasRole('super_admin') || $this->hasRole('partner')),
            'affiliate' => $this->affiliate,
            'is_affiliate' => (bool)$this->is_affiliate,
            'img' => $this->getImage(),
            'active' => (bool)$this->active,
            'affiliate_count' => User::where('affiliate_id', auth()->id())->count(),
            'is_hash' => (bool)$this->is_hash,
            'force_reset_password' => (bool)$this->force_reset_password,
            'fill_hash' => !empty($this->hash)

        ];
    }
}
