<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * Get user belong to this order
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get product belong to this order
     */
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
