<?php


// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cart_id',
        'name',
        'phone',
        'email',
        'province',
        'district',
        'ward',
        'address_detail',
        'payment_method',
        'total',
        'status',
    ];
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity') 
                    ->withTimestamps();
    }
    public function cart()
{
    return $this->belongsTo(Cart::class, 'cart_id');
}


}
