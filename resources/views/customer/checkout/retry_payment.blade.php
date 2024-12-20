@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h2>Thực hiện lại thanh toán cho đơn hàng #{{ $order->id }}</h2>
        </div>
        <div class="card-body">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('checkout.retry.process', $order->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="payment_method">Chọn phương thức thanh toán</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        <option value="vnpay">VNPay</option>
                        <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    </select>
                </div>

                <div class="mt-4">
                    <h5 class="text-muted">Thông tin đơn hàng</h5>
                    <ul class="list-group">
                        @foreach($order->products as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" class="img-thumbnail mr-3" style="width: 60px;">
                                    <div>
                                        <h6>{{ $product->name }}</h6>
                                        <small>Số lượng: {{ $product->pivot->quantity }}</small>
                                    </div>
                                </div>
                                <span class="font-weight-bold">{{ number_format($product->price * $product->pivot->quantity, 0, ',', '.') }} VND</span>
                            </li>
                        @endforeach
                    </ul>
                    <h6 class="text-right mt-3">Tổng tiền: <strong>{{ number_format($order->total, 0, ',', '.') }} VND</strong></h6>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Thực hiện lại thanh toán</button>
                    <a href="{{ route('customer.profile.orders') }}" class="btn btn-secondary btn-lg">Quay lại danh sách đơn hàng</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
