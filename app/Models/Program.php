<?php

namespace App\Models;

use App\Traits\HelperMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Program
 *
 * @property int $id
 * @property string $name
 * @property string|null $img
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Server[] $servers
 * @property-read int|null $servers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Program newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Program newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Program query()
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Program whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Program extends Model implements HasMedia
{
    use HasFactory, HelperMedia;

    protected $guarded = [];
    protected $casts=[
        'countries'=>'array'
    ];

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }



    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getTotalPrice(){
        if(auth()->check() && auth()->user()->hasRole('partner')){
            $setting = Setting::first();
            return $this->price - ($this->price * $setting->discount_delegate_online);
        }else{
            return $this->price;
        }
    }
}
