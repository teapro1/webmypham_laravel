<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $userId = Auth::id();

        if (Auth::user()->role === 'admin') {
            $carts = Cart::with('user')->get();
            return view('admin.cart.index', compact('carts'));
        }

        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $items = is_string($cart->items) ? json_decode($cart->items, true) : $cart->items; 

            $cartItems = [];
            foreach ($items as $rowId => $item) {
                $cartItems[] = [
                    'rowId' => $rowId,
                    'name' => $item['name'],
                    'logo' => $item['logo'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                ];
            }

            $cartTotal = array_sum(array_map(function ($item) {
                return $item['price'] * $item['qty'];
            }, $cartItems));
        } else {
            $cartItems = [];
            $cartTotal = 0;
        }

        return view('customer.cart.index', compact('cartItems', 'cartTotal'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm không tồn tại.');
        }

        $quantity = $request->input('quantity', 1);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $items = is_string($cart->items) ? json_decode($cart->items, true) : [];

        if (isset($items[$product->id])) {
            $items[$product->id]['qty'] += $quantity;
        } else {
            $items[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'logo' => $product->logo,
                'price' => $product->price,
                'qty' => $quantity,
            ];
        }

        $cart->items = json_encode($items);
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $rowId)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $items = is_string($cart->items) ? json_decode($cart->items, true) : $cart->items; 

            if (isset($items[$rowId])) {
                $product = Product::find($rowId);
                
                if (!$product) {
                    return redirect()->back()->with('error', 'Sản phẩm không tồn tại.');
                }

                $requestedQty = (int) $request->input('quantity');
                if ($requestedQty > $product->stock) {
                    return redirect()->back()->with('error', 'Số lượng yêu cầu vượt quá số lượng tồn kho hiện tại.');
                }

                $items[$rowId]['qty'] = $requestedQty;
                $cart->items = json_encode($items);
                $cart->save();

                return redirect()->back()->with('success', 'Cập nhật giỏ hàng thành công.');
            }
        }

        return redirect()->back()->with('error', 'Không tìm thấy giỏ hàng.');
    }


    public function remove($rowId)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $items = is_string($cart->items) ? json_decode($cart->items, true) : $cart->items; 
            if (isset($items[$rowId])) {
                unset($items[$rowId]); 
                $cart->items = json_encode($items);
                $cart->save();

                return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
            }
        }

        return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng.');
    }
}
    