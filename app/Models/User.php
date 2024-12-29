<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\BillStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Observers\ProductObserve;
use App\Observers\UserObServe;
use App\Traits\HelperMedia;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use JeffGreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $username
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $device_token
 * @property int $group_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property string|null $ratio
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Balance[] $balances
 * @property-read int|null $balances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read mixed $balance
 * @property-read \App\Models\Group $group
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRatio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia, FilamentUser
{
    // TwoFactorAuthenticatable for tow auth
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HelperMedia, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'group_id',
        'phone',
        'address',
        'device_token',
        'ratio',
        'user_id',
        'active',
        'ratio_online',
        'token',
        'is_show',
        'affiliate',
        'is_affiliate',
        'win_by_affiliate',
        'affiliate_id',
        'is_fixed_group',
        'hash',
        'is_hash',
        'force_reset_password',
        'hook_api',
        'is_active_hook',
        'is_check_name',
        'expired_date',
        'order_hook'

    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_hash' => 'boolean',
        'force_reset_password' => 'boolean',
        'is_active_hook' => 'boolean'
    ];

    protected $with = [
        'group',
        'user'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        self::observe(UserObServe::class);
    }

    public function canAccessFilament(): bool
    {
        return auth()->check() && (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('partner'));
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function balances(): HasMany
    {
        return $this->hasMany(Balance::class);
    }

    public function getBalanceAttribute()
    {
        return \DB::table('balances')->where('user_id', $this->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()->total ?? 0;
    }

    public function getTotalPoint()
    {
        return \DB::table('points')->where('user_id', $this->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()->total ?? 0;

    }

    public function getTotalBalance()
    {
        return \DB::table('balances')->where('user_id', $this->id)->selectRaw('SUM(credit) - SUM(debit) as total')->first()->total ?? 0;
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function affiliates(): HasMany
    {
        return $this->hasMany(User::class, 'affiliate_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function getWinAttribute()
    {
        return \DB::table('balances')->where('user_id', $this->id)->selectRaw('Sum(ratio) as win')->first()?->win ?? 0;
    }

    public function getDebitAttribute()
    {
        //  return \DB::table('balances')->where('user_id',$this->id)->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])->selectRaw('Sum(debit) as debit')->first()?->debit??0;
        $orders = \DB::table('orders')->where('user_id', $this->id)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->where('status', OrderStatusEnum::COMPLETE->value)->selectRaw('SUM(price) as total')->first();
        $bills = \DB::table('bills')->where('user_id', $this->id)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->where('status', BillStatusEnum::COMPLETE->value)->selectRaw('SUM(price) as total')->first();
        return $orders?->total ?? 0 + $bills?->total ?? 0;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_user')->withPivot(['price']);
    }


    public function getRatioAttribute($value)
    {
        if ($this->user && !$this->user->is_show) {
            return Setting::first()->fixed_ratio;
        }
        return $value;
    }

    public function affiliate_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affiliate_id', 'id');
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function getTotalBay()
    {
        $bills = \DB::table('bills')->where('user_id', $this->id)->where('status', 'complete')
            ->whereBetween('created_at', [now()->subDays(30), now()])->sum('bills.price');
        $order = \DB::table('orders')->where('user_id', $this->id)->where('status', 'complete')
            ->whereBetween('created_at', [now()->subDays(30), now()])->sum('orders.price');
        return ($bills ?? 0 + $order ?? 0);
    }

    public function getTotalBayInMonth()
    {
        $bills = \DB::table('bills')->where('user_id', $this->id)->where('status', 'complete')
            ->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('bills.price');
        $order = \DB::table('orders')->where('user_id', $this->id)->where('status', 'complete')
            ->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('orders.price');
        return ($bills ?? 0 + $order ?? 0);
    }

}
