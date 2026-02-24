<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'url',
        'notes',
        'user_id',
        'current_price',
        'price_checked_at',
    ];

    protected $casts = [
        'price_checked_at' => 'datetime',
        'current_price' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($subscription) {
            $subscription->user_id = $subscription->user_id ?: auth()->id();
        });
    }
}
