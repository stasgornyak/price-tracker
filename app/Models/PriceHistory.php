<?php

namespace App\Models;

use Database\Factories\PriceHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    /** @use HasFactory<PriceHistoryFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $table = 'price_history';

    protected $fillable = ['subscription_id', 'price', 'recorded_at'];

    protected $casts = [
        'price' => 'float',
        'recorded_at' => 'datetime',
    ];

    /** @return BelongsTo<Subscription, $this> */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
