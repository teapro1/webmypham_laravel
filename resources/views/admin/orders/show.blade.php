@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-light">
        <div class="card-header bg-primary text-white text-center">
            <h2>Chi Tiết Đơn Hàng #{{ $order->id }}</h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-success">
                        <i class="fas fa-user-circle"></i> <strong>Khách Hàng:</strong> {{ $order->name }}
                    </h5>
                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $order->email }}</p>
                    <p><strong><i class="fas fa-phone"></i> Số Điện Thoại:</strong> {{ $order->phone }}</p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-success"><i class="fas fa-map-marker-alt"></i> <strong>Địa Chỉ:</strong></h5>
                    <p>{{ $order->address_detail }}, {{ $order->ward_name }}, {{ $order->district_name }}, {{ $order->province_name }}</p>
                    <p>
                        <strong><i class="fas fa-money-check-alt"></i> Phương Thức Thanh Toán:</strong>
                        @switch($order->payment_method)
                            @case('cod')
                                <i class="fas fa-money-bill-wave"></i> Thanh toán khi nhận hàng (COD)
                                @break
                            @case('vnpay')
                                <i class="fab fa-cc-visa"></i> Thanh toán qua VNPAY
                                @break
                            @case('bank_transfer')
                                <i class="fas fa-exchange-alt"></i> Chuyển khoản ngân hàng
                                @break
                            @case('qr_code')
                                <i class="fas fa-qrcode"></i> Quét mã QR
                                @break
                            @default
                                <i class="fas fa-credit-card"></i> Phương thức khác
                        @endswitch
                    </p>
                    <p><strong><i class="fas fa-calendar-alt"></i> Ngày Tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <h3 class="mt-4"><i class="fas fa-list-alt"></i> Danh Sách Sản Phẩm</h3>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th style="text-align:center">Logo</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->isNotEmpty())
                        @foreach($products as $product)
                            <tr>
                                <td class="text-center">
                                    <img src="{{ Storage::url($product->logo) }}" alt="{{ $product->name }}" class="product-logo">
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ number_format($product->price * $product->pivot->quantity, 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">Không có sản phẩm nào trong đơn hàng.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="row mt-4">
                <div class="col-md-12 text-end">
                    <h4 class="text-danger"><strong><i class="fas fa-tags"></i> Tổng Đơn Hàng:</strong> {{ number_format($order->total, 0, ',', '.') }} VND</h4>
                </div>
            </div>

            <!-- Thao tác nút quay lại và xóa đơn hàng -->
            <div class="row mt-3">
                <div class="col-md-12 text-end">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Quay Lại
                    </a>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash-alt"></i> Xóa Đơn Hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom styles -->
<style>
    .card-header h2 {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 0;
    }

    .table th {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .product-logo {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .text-center {
        text-align: center;
    }

    /* Tăng kích thước biểu tượng */
    .fas, .fab {
        margin-right: 5px;
    }
</style>
@endsection
