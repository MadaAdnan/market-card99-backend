<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'img'=>$this->getImage(),
            'price'=>$this->pivot->price-($this->pivot->price*$this->discount()),
        ];
    }

    public function discount(){
       $setting= Setting::first();
       if($setting){
           return $setting->discount_online??0;
       }
       return 0;
    }
}
