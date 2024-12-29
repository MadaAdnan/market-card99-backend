<?php

namespace App\Models;

use App\Traits\HelperMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Server
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $api
 * @property string|null $img
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $username
 * @property string|null $password
 * @property string|null $is_active
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Country[] $countries
 * @property-read int|null $countries_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Program[] $programs
 * @property-read int|null $programs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Server active()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUsername($value)
 * @mixin \Eloquent
 */
class Server extends Model implements HasMedia
{
    use HasFactory,HelperMedia;
    protected $guarded=[];

    public function scopeActive($q){
        return $this->where('is_active','active');
    }


    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
