@extends('layouts.app')

@section('title', 'Đơn Hàng Chưa Thanh Toán (VNPAY)')

@section('content')
<div class="container">
    
    <!-- Thông báo sau khi hủy đơn hàng -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @forelse($unpaidVnpayOrders as $order)
            <div class="col-md-12 mb-4"> <!-- Thay đổi từ col-md-6 sang col-md-12 -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Đơn hàng #{{ $order->id }}</h5>
                        <span class="badge badge-warning">Chưa thanh toán</span>
                    </div>
                    <div class="card-body">
                        <h6>Thông tin đơn hàng:</h6>
                        <ul class="list-group mb-3">
                            @foreach($order->products as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" class="img-thumbnail mr-3" style="width: 60px;">
                                        <div>
                                            <h6>{{ $product->name }}</h6>
                                            <small>Số lượng: {{ $product->pivot->quantity }}</small>
                                        </div>
                                    </div>
                                    <span>{{ number_format($product->price * $product->pivot->quantity, 0, ',', '.') }} VND</span>
                                </li>
                            @endforeach
                        </ul>

                        <h6 class="text-right">Tổng tiền: <strong>{{ number_format($order->total, 0, ',', '.') }} VND</strong></h6>
                        
                        <div class="d-flex justify-content-between align-items-center">
    <div>
       
        <form action="{{ route('customer.profile.order.pay', $order->id) }}" method="POST" class="d-inline retry-payment">
            @csrf
            <button type="submit" class="btn btn-primary">Thanh Toán Lại</button>
        </form>

  
        <form action="{{ route('customer.orders.cancel', $order->id) }}" method="GET" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">Hủy Đơn Hàng</button>
        </form>
    </div>
</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>Không có đơn hàng VNPAY nào chưa thanh toán.</h4>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
