<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
class Cart extends Model
{
    protected $fillable = ['user_id', 'items'];

    protected $casts = [
        'items' => 'json', 
    ];
    public function content()
    {
        return json_decode($this->items, true) ?? [];
    }
    
    public function total()
    {
        $total = 0;
        $items = $this->content();
    
        foreach ($items as $item) {
            Log::info('Sản phẩm trong giỏ hàng:', $item);
    
            if (isset($item['price']) && isset($item['qty'])) { 
                $total += $item['price'] * $item['qty']; 
            } else {
                Log::error('Missing keys in cart item', ['item' => $item]);
            }
        }
    
        return $total;
    }
    
    
    
    public static function add($productId, $quantity)
    {
        $userId = Auth::id();
        $cart = self::firstOrCreate(['user_id' => $userId]);
    
        $items = json_decode($cart->items, true) ?? [];

        $product = Product::find($productId);
    
        if ($product) {
            if (isset($items[$productId])) {
         
                $items[$productId]['qty'] += $quantity;
            } else {
             
                $items[$productId] = [
                    'id' => $product->id, 
                    'name' => $product->name,
                    'logo' => $product->logo,
                    'price' => $product->price,
                    'qty' => $quantity,
                ];
            }
    
            $cart->items = json_encode($items);
            $cart->save();
        }
    }
    
    
    public function updateCart($productId, $quantity)
    {
        $items = json_decode($this->items, true);
    
        if (isset($items[$productId])) {
            $items[$productId]['qty'] = $quantity; 
    
            $this->items = json_encode($items);
            $this->save();
        }
    }
    
    public function remove($productId)
    {
        $items = json_decode($this->items, true);
    
        if (isset($items[$productId])) {
            
            Log::info('Đang xóa sản phẩm khỏi giỏ hàng:', ['productId' => $productId]);
    
            
            unset($items[$productId]);
    
            $this->items = json_encode($items);
            $this->save();
        }
    }
    

public function user()
{
    return $this->belongsTo(User::class);
}

}