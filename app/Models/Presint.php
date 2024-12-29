<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presint extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function asks(){
        return $this->belongsToMany(Ask::class)->withPivot(['answer']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
