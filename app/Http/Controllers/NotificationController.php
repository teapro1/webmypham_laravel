<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function index()
    {
        $unpaidVnpayOrders = Auth::user()->orders()
            ->where('status', 'pending') 
            ->where('payment_method', 'vnpay')  
            ->get();

        return view('notifications.index', compact('unpaidVnpayOrders'));
    }
}
