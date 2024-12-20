<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 
use App\Models\Order; 
use App\Models\Address;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Services\AddressService;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('customer.profile.index', compact('user')); 
    }
    public function addresses()
    {
        $user = Auth::user();
        $addresses = AddressService::getFullAddresses($user->id);
        $defaultAddress = AddressService::getDefaultAddress($user->id); 
    
        return view('customer.profile.addresses', compact('addresses', 'defaultAddress'));
    }
  
    public function getDistricts($provinceId)
{
    $districts = AddressService::getDistricts($provinceId);
    return response()->json($districts);
}

public function getWards($districtId)
{
    $wards = AddressService::getWards($districtId);
    return response()->json($wards);
}

public function showAddresses()
{
    $userId = Auth::id();
    $fullAddresses = AddressService::getFullAddresses($userId);
    $defaultAddress = AddressService::getDefaultAddress($userId);

    

    return view('customer.profile.addresses', compact('fullAddresses', 'defaultAddress'));
}
public function showUnpaidOrders()
{
    $pendingOrders = Order::with('products') // Eager load products
        ->where('user_id', Auth::id())
        ->where('status', 'pending')
        ->where('payment_method', 'vnpay')
        ->get();

    return view('customer.checkout.unpaid', compact('pendingOrders'));
}


public function cancelPendingOrders()
{
    Order::where('user_id', Auth::id())
    ->where('status', 'pending')
    ->where('payment_method', 'vnpay')
    ->update([
        'status' => 'canceled',
        'cart_id' => null,
    ]);

return redirect()->route('customer.profile.index')->with('success', 'Đã hủy đơn hàng thành công.');
}


public function showAddAddressForm()
{
    $provinces = AddressService::getProvinces();
    return view('customer.profile.address.add', compact('provinces'));
}


    public function addAddress(Request $request)
    {
        $request->validate([
            'province' => 'required|string|max:10',
            'district' => 'required|string|max:10',
            'ward' => 'required|string|max:10',
        ]);
    
        Address::create([
            'user_id' =>Auth::id(),
            'detail_address' => $request->detail_address,
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
        ]);
    
        return redirect()->back()->with('success', 'Địa chỉ đã được thêm thành công.');
}

public function setDefaultAddress(Request $request, $addressId)
{
    $user = Auth::user(); 
    $address = Address::where('id', $addressId)
                      ->where('user_id', $user->id)
                      ->first();

    if ($address) {
        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        return redirect()->back()->with('success', 'Đã đặt địa chỉ mặc định thành công.');
    }

    return redirect()->back()->with('error', 'Địa chỉ không tồn tại.');
}

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255', 
        ]);
    
        $user = Auth::user();
        if (!$user instanceof User) {
            return redirect()->back()->withErrors(['msg' => 'User không tồn tại.']);
        }
    
        $user->name = $request->name;
        $user->address = $request->address; 
        $user->save(); 
    
        return redirect()->route('customer.profile.index')->with('success', 'Thông tin cá nhân đã được cập nhật.');
    }
        public function editAdd($id)
    {
        $address = AddressService::getAddressById($id);
        if (!$address) {
            return redirect()->back()->with('error', 'Địa chỉ không tồn tại.');
        }
        $provinces = AddressService::getProvinces();
        $districts = AddressService::getDistricts($address->province);
        $wards = AddressService::getWards($address->district);
        
        return view('customer.profile.address.edit', compact('address', 'provinces', 'districts', 'wards'));
    }
    
    public function updateAdd(Request $request, $id)
    {
        $request->validate([
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
            'detail_address' => 'required|string|max:255',
        ]);
    
        $address = Address::findOrFail($id);
        $address->update([
            'province' => $request->province,
            'district' => $request->district,
            'ward' => $request->ward,
            'detail_address' => $request->detail_address,
        ]);
    
        return redirect()->route('customer.profile.addresses')->with('success', 'Cập nhật địa chỉ thành công!');
    }
    
    
    public function destroy($id)
    {
        $address = Address::find($id);
    
        if ($address) {
            $address->delete();
            return redirect()->route('customer.profile.addresses')->with('success', 'Địa chỉ đã được xóa thành công!');
        } else {
            return redirect()->route('customer.profile.addresses')->with('error', 'Địa chỉ không tồn tại!');
        }
    }
    
    public function orders()
    {
        $user = Auth::user();
    
        if ($user) {
            $orders = $user->orders()->paginate(6);
    
            foreach ($orders as $order) {
                // Lấy thông tin địa chỉ từ mối quan hệ hoặc từ một model khác
                $order->wardName = $order->address->ward->name ?? 'Không xác định';
                $order->districtName = $order->address->district->name ?? 'Không xác định';
                $order->provinceName = $order->address->province->name ?? 'Không xác định';
            }
    
            return view('customer.profile.orders', compact('orders'));
        }
    
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem đơn hàng.');
    }
    
    
    public function orderDetail($id)
    {
        $order = Order::findOrFail($id);
        return view('customer.profile.orderDetail', compact('order'));
    }
    public function payOrder($id)
    {
        $order = Order::findOrFail($id);
        Log::info('User attempting to pay for order: ' . $order->id);
    
        if ($order->status === 'pending') {
            Log::info('Order is pending, redirecting to retry payment.');
            return redirect()->route('customer.checkout.retry', ['order' => $order->id]);
        }
    
        return redirect()->route('customer.profile.orders')->with('success', 'Thanh toán thành công.');
    }
    
    

    public function showChangePasswordForm()
    {
        return view('customer.profile.change_password');
    }
    
    public function sendVerificationCode(Request $request)
    {
        Log::info('Bắt đầu gửi mã xác minh');
    
        $verificationCode = rand(100000, 999999);
        session(['verification_code' => $verificationCode]);
    
        Log::info('Mã xác minh: ' . $verificationCode);
        
        try {
            Mail::to($request->user()->email)->send(new VerificationCodeMail($verificationCode));
            Log::info('Email gửi thành công tới: ' . $request->user()->email);
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra trong quá trình gửi mã xác minh.'], 500);
        }
    
        Log::info('Gửi mã xác minh hoàn tất');
        return response()->json(['success' => 'Mã xác minh đã được gửi.']);
    }
    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'verification_code' => 'required|numeric|digits:6',
        ]);
    
        if ($request->verification_code != session('verification_code')) {
            return back()->withErrors(['verification_code' => 'Mã xác minh không chính xác.']);
        }

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }
    
        $user = User::find(Auth::id());
        if ($user) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } else {
            return back()->withErrors(['user' => 'Không tìm thấy người dùng.']);
        }
        session()->forget('verification_code');
    
        return redirect()->back()->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }
}
