<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'price_in_PLN',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
//            ->withTimestamps()->withPivot('order_id','product_id');
    }
}
