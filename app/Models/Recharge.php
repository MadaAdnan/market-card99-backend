<?php

namespace App\Models;

use App\Traits\HelperMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Recharge extends Model implements  HasMedia
{
    use HasFactory,HelperMedia;
    protected $guarded=[];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }
}
