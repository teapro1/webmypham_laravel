<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10);
        foreach ($orders as $order) {
            $order->province_name = AddressService::getProvinceName($order->province);
            $order->district_name = AddressService::getDistrictName($order->district);
            $order->ward_name = AddressService::getWardName($order->ward);
        }
        return view('admin.orders.index', compact('orders'));
    }
    public function show($id)
    {
        $order = Order::findOrFail($id);
        $products = $order->products()->withPivot('quantity')->get();
        $order->province_name = AddressService::getProvinceName($order->province);
        $order->district_name = AddressService::getDistrictName($order->district);
        $order->ward_name = AddressService::getWardName($order->ward);
        return view('admin.orders.show', compact('order', 'products'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đơn hàng đã được xóa thành công.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,canceled,shipping,shipped,delivered',
        ]);

        $order = Order::findOrFail($id);

        if ($request->input('status') == 'canceled' && $order->status != 'paid') {
            $cart = Cart::find($order->cart_id);
            if ($cart) {
                $cart->delete(); 
            }
        }

        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Trạng thái đơn hàng đã được cập nhật.');
    }
 
}
