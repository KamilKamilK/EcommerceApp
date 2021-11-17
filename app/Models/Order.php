<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'price_in_PLN',
        'order_status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_item','order_id','product_id')
            ->withTimestamps()->withPivot('order_item');
    }

    // user_id
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
