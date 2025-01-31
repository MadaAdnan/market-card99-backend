<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Balance
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $credit
 * @property string|null $debit
 * @property string|null $total
 * @property string|null $info
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Balance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Balance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Balance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Balance whereUserId($value)
 * @mixin \Eloquent
 */
class Balance extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }
}
