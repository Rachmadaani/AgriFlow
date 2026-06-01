<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Harvest extends Model
{
    protected $fillable = ['plant_id', 'user_id', 'date', 'weight_kg', 'price_per_kg'];

    protected $casts = [
        'date' => 'date',
        'weight_kg' => 'decimal:2',
        'price_per_kg' => 'decimal:2'
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRevenueAttribute()
    {
        return $this->weight_kg * $this->price_per_kg;
    }
}
