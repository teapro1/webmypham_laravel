<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
class VNPayService
{
    private $vnp_TmnCode;
    private $vnp_HashSecret;
    private $vnp_Url;
    private $vnp_ReturnUrl;

    public function __construct()
    {
        $this->vnp_TmnCode = env('VNP_TMNCODE'); // Mã Tm Code của bạn
        $this->vnp_HashSecret = env('VNP_HASHSECRET'); // Chuỗi bí mật
        $this->vnp_Url = env('VNP_URL'); // URL thanh toán VNPay
        $this->vnp_ReturnUrl = env('VNP_RETURNURL'); // URL trả về sau thanh toán
    }

    public function createPaymentUrl($orderId, $amount)
    {
        $vnp_Data = [];
        $vnp_Data['vnp_Version'] = '2.1.0';
        $vnp_Data['vnp_Command'] = 'pay';
        $vnp_Data['vnp_TmnCode'] = $this->vnp_TmnCode;
        $vnp_Data['vnp_Amount'] = $amount * 100; 
        $vnp_Data['vnp_CurrCode'] = 'VND';
        $vnp_Data['vnp_TxnRef'] = $orderId;
        $vnp_Data['vnp_OrderInfo'] = "Thanh toán đơn hàng #$orderId";
        $vnp_Data['vnp_OrderType'] = 'billpayment';
        $vnp_Data['vnp_Locale'] = 'vn';
        $vnp_Data['vnp_ReturnUrl'] = $this->vnp_ReturnUrl;
        $vnp_Data['vnp_IpAddr'] = request()->ip();
        $vnp_Data['vnp_CreateDate'] = date('YmdHis');

        ksort($vnp_Data); 
        $query = http_build_query($vnp_Data); 
        $vnp_SecureHash = hash_hmac('sha512', $query, $this->vnp_HashSecret); 
        $vnp_Data['vnp_SecureHash'] = $vnp_SecureHash;
        
        return $this->vnp_Url . '?' . http_build_query($vnp_Data);
    }

    public function validateResponse($vnpData)
    {
        $secureHash = $vnpData['vnp_SecureHash'];
        unset($vnpData['vnp_SecureHash']);
        
     
        $secretKey = $this->vnp_HashSecret; 
    
     
        ksort($vnpData);
        $dataHash = hash_hmac('sha512', http_build_query($vnpData), $secretKey); 
        
     
        Log::info('Generated Hash: ' . $dataHash);
        Log::info('Received Hash: ' . $secureHash);
        
        return $dataHash === $secureHash;
    }
    
}
