<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use User;

class Plan extends Model
{
    protected $table = "plans";
    protected $fillable = [
        'name',
        'description',
        'price',
        'billing_cycles',
        'stripe_price_id',
        'stripe_product_id',
        'currency',
        'is_active',
        'features',
    ];
    protected $casts = [
        'billing_cycles'=> 'array',
        'is_active' => 'boolean',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
