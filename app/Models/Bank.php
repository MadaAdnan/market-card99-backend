<?php

namespace App\Models;

use App\Traits\HelperMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Bank extends Model implements HasMedia
{
    use HasFactory,HelperMedia;
    protected $guarded=[];
    protected $casts=[
        'is_active' => 'boolean'
    ];

}
