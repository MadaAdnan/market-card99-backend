<?php

namespace App\Models;

use App\Traits\HelperMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Slider
 *
 * @property int $id
 * @property string $img
 * @property string|null $face
 * @property string|null $telegram
 * @property string|null $instagram
 * @property string|null $whats
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereFace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereWhats($value)
 * @mixin \Eloquent
 */
class Slider extends Model implements HasMedia
{
    use HasFactory,HelperMedia;

    protected $guarded=[];
}
