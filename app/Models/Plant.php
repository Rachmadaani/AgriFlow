<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plant extends Model
{
    protected $fillable = ['name', 'default_price', 'planting_date', 'user_id'];

    protected $casts = [
        'planting_date' => 'date',
        'default_price' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function harvests(): HasMany
    {
        return $this->hasMany(Harvest::class);
    }
}
