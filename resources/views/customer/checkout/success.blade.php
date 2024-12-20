@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công')

@section('content')
<div class="container mt-5">
    @if(session('message'))
        <div class="alert alert-success text-center">
            {{ session('message') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h2>Chi Tiết Đơn Hàng #{{ $order->id }}</h2>
        </div>
        <div class="card-body">
            <p><strong>Khách Hàng:</strong> {{ $order->name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Số Điện Thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Địa Chỉ:</strong> {{ $order->address_detail }}, {{ $wardName }}, {{ $districtName }}, {{ $provinceName }}</p>

            <h3>Danh Sách Sản Phẩm</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ảnh Sản Phẩm</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" width="70" height="70">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ number_format($product->price * $product->pivot->quantity, 0, ',', '.') }} VND</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

         
            <div class="text-right mt-4" style="text-align:right">
                <h4><strong style="color: red;">Thành Tiền:</strong> {{ number_format($order->total, 0, ',', '.') }} VND</h4>
            </div>
        </div>
    </div>
    <!-- Back to Home Button -->
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary btn-block mt-4">
            <i class="fas fa-arrow-left"></i> Quay lại trang chủ
        </a>
</div>
@endsection
