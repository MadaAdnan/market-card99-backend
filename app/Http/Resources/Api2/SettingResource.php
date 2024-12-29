<?php

namespace App\Http\Resources\Api2;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
            'logo'=>$this->getImage(),
            'phone'=>$this->phone,
            'news'=>$this->news,
            'is_open'=>(bool)$this->is_open,
            'asks'=>$this->info_present,
            'msg_close'=>$this->msg_close,
            'whatsapp'=>isset($this->social['whatsapp'])?ltrim(ltrim($this->social['whatsapp'],'00'),'+'):'',
            'telegram'=>isset($this->social['telegram'])?$this->social['telegram']:'',
            'facebook'=>isset($this->social['facebook'])?$this->social['facebook']:'',
            'instagram'=>isset($this->social['instagram'])?$this->social['instagram']:'',
            'widget1'=>$this->widget1,
            'img1'=>$this->getImage('widget1'),
            'widget2'=>$this->widget2,
            'img2'=>$this->getImage('widget2'),
            'widget3'=>$this->widget3,
            'img3'=>$this->getImage('widget3'),
            'widget4'=>$this->widget4,
            'img4'=>$this->getImage('widget4'),
            'about'=>$this->about,
        ];
    }
}
