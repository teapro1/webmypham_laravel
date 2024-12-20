@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Chi Tiết Đơn Hàng #{{ $order->id }}</h1>
    
    <div class="card shadow-sm mb-4 border-light">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-receipt"></i> Thông Tin Đơn Hàng</h5>
            <span><i class="fas fa-clock"></i> {{ $order->created_at->format('H:i:s d/m/Y') }}</span>
        </div>
        <div class="card-body">
            <p><strong><i class="fas fa-info-circle"></i> Tình Trạng:</strong> 
                @switch($order->status)
                    @case('pending')
                        <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> Đang Chờ Thanh Toán</span>
                        @break
                    @case('paid')
                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Đã Thanh Toán</span>
                        @break
                    @case('shipping')
                        <span class="badge bg-info"><i class="fas fa-truck"></i> Đang Giao Hàng</span>
                        @break
                    @case('shipped')
                        <span class="badge bg-secondary"><i class="fas fa-box-open"></i> Đã Giao Hàng</span>
                        @break
                    @case('delivered')
                        <span class="badge bg-success"><i class="fas fa-gift"></i> Đã Nhận Hàng</span>
                        @break
                    @case('canceled')
                        <span class="badge bg-danger"><i class="fas fa-ban"></i> Đã Hủy</span>
                        @break
                    @default
                        <span class="badge bg-secondary"><i class="fas fa-question-circle"></i> {{ $order->status }}</span>
                @endswitch
            </p>
            <p><strong><i class="fas fa-user"></i> Khách Hàng:</strong> {{ $order->name }}</p>
            <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $order->email }}</p>
            <p><strong><i class="fas fa-phone"></i> Số Điện Thoại:</strong> {{ $order->phone }}</p>
            <p><strong><i class="fas fa-map-marker-alt"></i> Địa Chỉ Giao Hàng:</strong> 
                {{ $order->address_detail }}, 
                {{ App\Services\AddressService::getWardName($order->ward) }},
                {{ App\Services\AddressService::getDistrictName($order->district) }},
                {{ App\Services\AddressService::getProvinceName($order->province) }}
            </p>
            <p><strong><i class="fas fa-wallet"></i> Phương Thức Thanh Toán:</strong>
                @switch($order->payment_method)
                    @case('vnpay')
                        <i class="fas fa-credit-card"></i> Thanh Toán Qua VNPay
                        @break
                    @case('qr')
                        <i class="fas fa-qrcode"></i> Thanh Toán Bằng Mã QR
                        @break
                    @case('transfer')
                        <i class="fas fa-university"></i> Chuyển Khoản Ngân Hàng
                        @break
                    @case('cod')
                        <i class="fas fa-money-bill-wave"></i> Thanh Toán Khi Nhận Hàng (COD)
                        @break
                    @default
                        <i class="fas fa-question-circle"></i> Phương Thức Không Xác Định
                @endswitch
            </p>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-light">
        <div class="card-body">
            <h2 class="mb-3">Sản Phẩm</h2>
            @if($order->products && $order->products->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hình Ảnh</th>
                                <th>Tên Sản Phẩm</th>
                                <th>Số Lượng</th>
                                <th>Giá</th>
                                <th>Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->products as $product)
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 80px; height: auto;">
                                    </td>
                                    <td>
                                        <strong>{{ $product->name }}</strong><br>
                                        <div id="details-{{ $product->id }}" class="additional-details" style="display:none;">
                                            <p>Mô tả: {{ $product->description }}</p>
                                        </div>
                                    </td>
                                    <td>{{ $product->pivot->quantity }}</td>
                                    <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                                    <td>{{ number_format($product->price * $product->pivot->quantity, 0, ',', '.') }} VNĐ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Không có sản phẩm nào trong đơn hàng.</p>
            @endif

            <p class="lead mt-3"><i class="fas fa-money-check-alt"></i> Tổng cộng: <strong>{{ number_format($order->total, 0, ',', '.') }} VNĐ</strong></p>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('customer.profile.orders') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Trở Về Danh Sách Đơn Hàng
        </a>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 15px;
    }

    .badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    img {
        border-radius: 8px;
    }

    .btn-primary {
        padding: 0.75rem 2rem;
        font-size: 1rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        // Toggle additional product details
        $('tbody tr').on('click', function() {
            var productId = $(this).find('td:nth-child(2)').data('id'); // Get product ID
            $('#details-' + productId).toggle(); // Toggle display of additional details
        });
    });
</script>
@endsection
