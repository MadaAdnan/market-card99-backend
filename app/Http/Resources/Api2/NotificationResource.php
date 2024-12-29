<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'body' => $this->data['body'],
            'img' =>isset($this->data['img'])&& \Str::length($this->data['img'])>10?filter_var($this->data['img'],FILTER_VALIDATE_URL)?$this->data['img']:asset('storage/'.$this->data['img']):'',
            'title' => $this->data['title'],
            'color'=>isset($this->data['color'])?$this->data['color']:'black',
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
