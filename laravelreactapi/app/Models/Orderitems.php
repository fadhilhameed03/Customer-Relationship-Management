<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitems extends Model
{
    use HasFactory;
    protected $table = 'orderitems';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'product_id','id');
    }
}
