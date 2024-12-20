@extends('layouts.app')

@section('title', 'Danh Sách Đơn Hàng')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Danh Sách Đơn Hàng</h1>
    <div class="row">
        @foreach($orders as $order)
            <div class="col-12 mb-4"> <!-- Full width column -->
                <div class="card shadow-sm h-100 border border-light">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-receipt"></i> Đơn Hàng #{{ $order->id }}</h5>
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
                
                        <h6 class="mt-3"><i class="fas fa-box-open"></i> Sản Phẩm:</h6>
                        @if($order->products && $order->products->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col"><i class="fas fa-image"></i> Logo</th>
                                            <th scope="col"><i class="fas fa-tag"></i> Tên Sản Phẩm</th>
                                            <th scope="col"><i class="fas fa-sort-numeric-up"></i> Số Lượng</th>
                                            <th scope="col"><i class="fas fa-dollar-sign"></i> Giá Tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->products as $product)
                                            <tr>
                                                <td>
                                                    <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                </td>
                                                <td>
                                                    <strong>{{ $product->name }}</strong>
                                                    <div id="details-{{ $product->id }}" class="additional-details" style="display:none;">
                                                        <p>Mô tả: {{ $product->description }}</p>
                                                    </div>
                                                </td>
                                                <td>{{ $product->pivot->quantity }}</td>
                                                <td>{{ number_format($product->price, 0) }} VNĐ</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Không có sản phẩm nào trong đơn hàng.</p>
                        @endif

                        <p class="lead mt-3"><i class="fas fa-money-check-alt"></i> Tổng cộng: <strong>{{ number_format($order->total, 0, ',', '.') }} VNĐ</strong></p>

                        <a href="{{ route('customer.profile.orderDetail', $order->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Xem Chi Tiết</a>

                        @if($order->status == 'pending' && in_array($order->payment_method, ['vnpay', 'qr', 'transfer'])) 
                            <form action="{{ route('customer.profile.order.pay', $order->id) }}" method="POST" class="d-inline retry-payment">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-redo-alt"></i> Thanh Toán Lại</button>
                            </form>
                            @elseif($order->status == 'pending' && in_array($order->payment_method, ['cod'])) 
                            <a href="{{ route('customer.orders.cancel', $order->id) }}" class="btn btn-danger btn-sm">Hủy Đơn Hàng</a>
                        @elseif($order->status == 'pending' && $order->payment_method == 'cod')
                            <span class="badge bg-info"><i class="fas fa-truck"></i> Thanh Toán Khi Nhận Hàng</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Phân trang -->
    <div class="row">
        <div class="col-md-12">
            {{ $orders->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
</div>
<div class="text-center mb-4">
    <a href="{{ route('customer.profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
</div>
@endsection

<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    table th, table td {
        vertical-align: middle;
        text-align: center;
    }

    .badge {
        padding: 0.5rem;
        font-size: 0.875rem;
    }

    .btn i {
        margin-right: 5px;
    }

    img {
        border-radius: 8px;
    }
    .bg-white {
        --bs-bg-opacity: 0 !important;
    }
</style>

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Add FontAwesome Icons -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Xử lý sự kiện submit của form thanh toán lại
    $('form.retry-payment').on('submit', function(e) {
        e.preventDefault(); // Ngăn chặn hành vi mặc định

        var form = $(this);
        var url = form.attr('action'); // Lấy URL từ thuộc tính action của form

        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(), // Gửi dữ liệu của form
            success: function(response) {
                alert(response.message); // Hiển thị thông báo
                // Nếu cần, có thể cập nhật lại giao diện ở đây
                window.location.reload(); // Làm mới trang
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra! Vui lòng thử lại.'); // Thông báo lỗi
            }
        });
    });

    // Xử lý sự kiện click để hiển thị thêm chi tiết sản phẩm
    $('tbody tr').on('click', function() {
        var productId = $(this).data('id');
        $('#details-' + productId).toggle(); // Chuyển đổi trạng thái hiển thị của chi tiết
    });
});
</script>
@endsection
