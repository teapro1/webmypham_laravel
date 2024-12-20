<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\AddressService;
use App\Services\VNPayService;
use App\Models\Address;

class CheckoutController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
  
$addresses = AddressService::getFullAddresses($userId);
$defaultAddress = AddressService::getDefaultAddress($userId);

    return view('customer.checkout.checkout', compact('addresses', 'defaultAddress'));
    }

    public function process(Request $request)
    {
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'selected_address' => 'required|exists:addresses,id', // Validate the selected address
        ]);
    
        $cart = Cart::where('user_id', Auth::id())->first();
    
        if (!$cart) {
            return redirect()->back()->with('error', 'Giỏ hàng không tồn tại.');
        }
        $selectedAddress = Address::find($validatedData['selected_address']);
    
        if (!$selectedAddress) {
            return redirect()->back()->with('error', 'Địa chỉ không tồn tại. Vui lòng thêm địa chỉ trước khi thanh toán.');
        }
    
        $existingOrder = Order::where('cart_id', $cart->id)->first();
    
        if ($existingOrder) {
            if ($existingOrder->status === 'pending') {
                return redirect()->back()->with('error', 'Giao dịch đã tồn tại và đang chờ thanh toán. Vui lòng kiểm tra lại.');
            } else {
                return redirect()->back()->with('error', 'Giao dịch đã tồn tại hoặc đã được xử lý.');
            }
        }
        $order = new Order();
        $order->user_id = Auth::id();
        $order->cart_id = $cart->id;
        $order->name = $validatedData['name'];
        $order->phone = $validatedData['phone'];
        $order->email = $validatedData['email'];
        $order->province = $selectedAddress->province; // Use the selected address's province ID
        $order->district = $selectedAddress->district; // Use the selected address's district ID
        $order->ward = $selectedAddress->ward; // Use the selected address's ward ID
        $order->address_detail = $selectedAddress->detail_address; // Use the selected address's detail address
        $order->total = $cart->total();
    
        if ($validatedData['payment_method'] === 'vnpay') {
            $order->payment_method = 'vnpay';
            $order->status = 'pending';
            $order->save();
    
            return $this->processVNPay($request, $order);
        } else {
            $order->payment_method = 'cod';
            $order->status = 'pending';
            $order->save();
    
            $items = json_decode($cart->items, true);
            foreach ($items as $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $order->products()->attach($product->id, ['quantity' => $item['qty']]);
    
                    $product->stock -= $item['qty'];
                    $product->save();
                }
            }
    
            $cart->delete();
            return $this->success($order->id);
        }
    }
    
    public function success($orderId)
    {
        $order = Order::with('products')->find($orderId);
    
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Đơn hàng không tồn tại.');
        }

        $wardName = AddressService::getWardName($order->ward);
        $districtName = AddressService::getDistrictName($order->district);
        $provinceName = AddressService::getProvinceName($order->province);
    
        return view('customer.checkout.success', [
            'order' => $order,
            'message' => 'Đơn hàng đã được đặt thành công!',
            'wardName' => $wardName,
            'districtName' => $districtName,
            'provinceName' => $provinceName,
        ]);
    }
    

    public function processVNPay(Request $request, $order = null)
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            Log::error('Giỏ hàng không tồn tại cho user_id: ' . Auth::id());
            return redirect()->back()->with('error', 'Giỏ hàng không tồn tại.');
        }

        if (!$order) {
            $order = new Order();
            $order->user_id = Auth::id();
            $order->cart_id = $cart->id;
            $order->name = $request->input('name');
            $order->phone = $request->input('phone');
            $order->email = $request->input('email');
            $order->province = $request->input('province');
            $order->district = $request->input('district');
            $order->ward = $request->input('ward');
            $order->address_detail = $request->input('address_detail');
            $order->payment_method = 'vnpay';
            $order->total = $cart->total();
            $order->status = 'pending';
            $order->save();
        }

        $vnpayService = new VNPayService();
        $paymentUrl = $vnpayService->createPaymentUrl($order->id, $order->total);
        Log::info('VNPay Payment URL: ' . $paymentUrl);

        return redirect($paymentUrl);
    }

    public function vnpayReturn(Request $request)
    {
        $vnpData = $request->all();
        $vnpayService = new VNPayService();
        Log::info('VNPay Response Data:', $vnpData);

        $order = Order::where('id', $vnpData['vnp_TxnRef'])->first();

        if ($order && $order->status == 'paid') {
            return redirect()->route('checkout')->with('error', 'Đơn hàng đã được xử lý.');
        }

        if ($vnpayService->validateResponse($vnpData)) {
            if ($vnpData['vnp_ResponseCode'] == '00') {
                $order->status = 'paid';
                $order->vnp_TxnRef = $vnpData['vnp_TxnRef'];
                $order->save();

                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $items = json_decode($cart->items, true);
                    foreach ($items as $item) {
                        $product = Product::find($item['id']);
                        if ($product) {
                            $order->products()->attach($product->id, ['quantity' => $item['qty']]);
                            $product->stock -= $item['qty'];
                            $product->save();
                        }
                    }
                    $cart->delete();
                }

                return $this->success($order->id);
            } else {
                Log::warning('VNPay thanh toán không thành công với mã: ' . $vnpData['vnp_ResponseCode']);
                $order->status = 'pending';
                $order->vnp_TxnRef = $vnpData['vnp_TxnRef'];
                $order->cart_id = null; 
                $order->save();

                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $items = json_decode($cart->items, true);
                    foreach ($items as $item) {
                        $product = Product::find($item['id']);
                        if ($product) {
                            $order->products()->attach($product->id, ['quantity' => $item['qty']]);
                            $product->stock -= $item['qty'];
                            $product->save();
                        }
                    }
                    $cart->delete();
                }

                return redirect()->route('checkout')->with('error', 'Đã xảy ra lỗi trong quá trình thanh toán. Bạn có thể thanh toán lại hoặc hủy đơn hàng.');
            }
        } else {
            Log::error('VNPay chữ ký bảo mật không hợp lệ.');
            return redirect()->route('checkout')->with('error', 'Xác thực thanh toán thất bại.');
        }
    }

    public function retryPayment($orderId)
    {
        $order = Order::find($orderId);
        if (!$order || $order->status !== 'pending') {
            return redirect()->route('checkout')->with('error', 'Đơn hàng không tồn tại hoặc không còn trong trạng thái chờ thanh toán.');
        }

        return view('customer.checkout.retry_payment', compact('order'));
    }

    public function retryPaymentProcess(Request $request, $orderId)
    {
        $validatedData = $request->validate([
            'payment_method' => 'required|string|in:vnpay,cod,qr',
        ]);

        $order = Order::find($orderId);
        if (!$order || $order->status !== 'pending') {
            return redirect()->route('checkout')->with('error', 'Đơn hàng không tồn tại hoặc không còn trong trạng thái chờ thanh toán.');
        }


        DB::transaction(function () use ($validatedData, $order) {
            if ($validatedData['payment_method'] === 'vnpay') {
                $order->payment_method = 'vnpay';
                $vnpayService = new VNPayService();
                $paymentUrl = $vnpayService->createPaymentUrl($order->id, $order->total);
                session(['payment_url' => $paymentUrl]);
                return redirect($paymentUrl);
            } else {
                $order->payment_method = 'cod';
                $order->status = 'pending'; 
                $order->cart_id = null;
                $order->save();

                $cart = Cart::where('user_id', Auth::id())->first();
                if ($cart) {
                    $items = json_decode($cart->items, true);
                    foreach ($items as $item) {
                        $product = Product::find($item['id']);
                        if ($product) {
                            $order->products()->attach($product->id, ['quantity' => $item['qty']]);
                            $product->stock -= $item['qty'];
                            $product->save();
                        }
                    }
                    $cart->delete();
                }
            }
        });
        return redirect()->back()->with('success', 'Đơn hàng đã được đặt thành công. Vui lòng kiểm tra trong mục Đơn Hàng');
    }

    public function cancelPendingOrders($orderId)
    {
        $order = Order::find($orderId);

        if ($order && $order->status == 'pending') {

            foreach ($order->products as $product) {
                $product->stock += $product->pivot->quantity;
                $product->save();
            }

            $order->status = 'canceled';
            $order->save();

            return redirect()->back()->with('success', 'Đơn hàng VNPay đã được hủy thành công.');
        }

        return redirect()->back()->with('error', 'Đơn hàng không tồn tại, đã được thanh toán hoặc không phải thanh toán VNPay.');
    }
}
