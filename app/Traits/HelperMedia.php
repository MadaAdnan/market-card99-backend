<?php

namespace App\Traits;

use App\Models\Product;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


trait HelperMedia
{
    use InteractsWithMedia;
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')->format('webp')->quality(80)->nonQueued();

    }

    public function getImage($collection='image'){

        if($this->hasMedia($collection)){
            return $this->getFirstMediaUrl($collection,'webp');

        }elseif($this->hasMedia('webp')){
            return $this->getFirstMediaUrl('webp','webp');

        }elseif(static::class==Product::class && $this->category->hasMedia($collection)){
            return $this->category->getFirstMediaUrl($collection,'webp');
        }elseif ($this->img){
            return asset('storage/'.$this->img);
        }
        return asset('assets/images/market.png');
    }

   public function getImages($collection='image'){
        $list=[];
        if($this->hasMedia($collection)){
            foreach ($this->getMedia($collection) as $media){
                $list[]=$media->getUrl('webp');
            }
        }
        return $list;
    }
}
