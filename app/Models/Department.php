<?php

namespace App\Models;

use App\Traits\HelperMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;

class Department extends Model implements HasMedia
{
    use HasFactory,HelperMedia;
    protected $guarded=[];


    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
