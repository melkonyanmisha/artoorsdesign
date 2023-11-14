<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;


class Review extends Model
{
    use HasFactory;

    // Disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'product_id',
        'text',
        'is_positive_like',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
