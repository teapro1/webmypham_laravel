@extends('layouts.admin')

@section('title', 'Quản lý Đơn Hàng')

@section('content')
<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 200px;">Tên Khách Hàng</th>
                    <th style="width: 150px;">Số Điện Thoại</th>
                    <th style="width: 300px;">Địa Chỉ</th>
                    <th style="width: 120px;">Tổng Tiền</th>
                    <th style="width: 180px;">Phương Thức Thanh Toán</th>
                    <th style="width: 150px;">Trạng Thái</th>
                    <th style="width: 150px;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="text-center">
                    <td>{{ $order->id }}</td>
                    <td class="text-start">{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td class="text-start">
                        {{ $order->address_detail }}
                        @if(!empty($order->ward_name)), {{ $order->ward_name }}@endif
                        @if(!empty($order->district_name)), {{ $order->district_name }}@endif
                        @if(!empty($order->province_name)), {{ $order->province_name }}@endif
                    </td>
                    <td>{{ number_format($order->total, 0, ',', '.') }} VND</td>
                    <td>
                        @switch($order->payment_method)
                            @case('cod')
                                <i class="fas fa-money-bill-wave"></i> COD
                                @break
                            @case('vnpay')
                                <i class="fab fa-cc-visa"></i> VNPAY
                                @break
                            @case('bank_transfer')
                                <i class="fas fa-exchange-alt"></i> Chuyển Khoản
                                @break
                            @case('qr_code')
                                <i class="fas fa-qrcode"></i> Quét QR
                                @break
                            @default
                                <i class="fas fa-credit-card"></i> Khác
                        @endswitch
                    </td>
                    <td>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select" onchange="this.form.submit()" style="width: 130px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Đang Chờ Thanh Toán</option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Đã Thanh Toán</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang Giao Hàng</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã Giao Hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã Nhận Hàng</option>
                                <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Đã Hủy</option>
                            </select>
                        </form>
                    </td>
                    <td class="d-flex justify-content-center align-items-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-eye"></i> Chi tiết
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Không có đơn hàng nào hiện có.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="d-flex justify-content-center">
            {{ $orders->links('vendor.pagination.bootstrap-4') }} <!-- Sử dụng Bootstrap 5 cho phân trang -->
        </div>
    @endif
</div>

<!-- Custom styles -->
<style>
    .table {
        border: 1px solid #dee2e6;
        margin-bottom: 1rem;
    }
    .table th, .table td {
        border: 1px solid #dee2e6;
        vertical-align: middle;
        padding: 15px; /* Tăng padding để tạo không gian hơn */
        white-space: nowrap; /* Ngăn không cho nội dung nhảy dòng */
        overflow: hidden; /* Ẩn phần nội dung thừa */
        text-overflow: ellipsis; /* Thêm dấu "..." cho nội dung bị ẩn */
    }
    .table-hover tbody tr:hover {
        background-color: #f2f2f2;
    }
    .btn-info, .btn-danger {
        transition: background-color 0.3s ease;
    }
    .btn-info {
        background-color: #0dcaf0;
        border-color: #0dcaf0;
    }
    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .btn-info:hover {
        background-color: #0ab8e1;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
</style>
@endsection
