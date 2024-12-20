@extends('layouts.admin')

@section('title', 'Danh Sách Giỏ Hàng')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Danh Sách Giỏ Hàng</h2>
    
    <table class="table table-hover align-middle shadow-sm">
        <thead class="table-dark text-center">
            <tr>
                <th>ID Người Dùng</th>
                <th>Tên Người Dùng</th>
                <th>Sản Phẩm</th>
                <th>Tổng Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carts as $cart)
                <tr>
                    <td class="text-center">{{ $cart->user_id }}</td>
                    <td>{{ $cart->user->name ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-info position-relative" onmouseover="showProducts('{{ $cart->user_id }}')" onmouseout="hideProducts('{{ $cart->user_id }}')">
                            <i class="fas fa-box"></i> {{ count(json_decode($cart->items, true)) }} sản phẩm
                        </button>
                        <div id="productList{{ $cart->user_id }}" class="product-list" style="display: none;">
                            @foreach(json_decode($cart->items, true) as $item)
                                <div class="product-item d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $item['logo']) }}" alt="{{ $item['name'] }}" class="product-logo">
                                    <div class="ml-2">
                                        <strong>{{ $item['name'] }}</strong><br>
                                        Giá: {{ number_format($item['price'], 0) }} VNĐ<br>
                                        Số lượng: {{ $item['qty'] }}
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-center">{{ number_format(array_reduce(json_decode($cart->items, true), function ($carry, $item) {
                        return $carry + ($item['price'] * $item['qty']);
                    }, 0), 0) }} VNĐ</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($carts->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            Không có giỏ hàng nào hiện có.
        </div>
    @endif
</div>

<style>
    .product-list {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 300px; /* Điều chỉnh độ rộng nếu cần */
        margin-top: 5px; /* Khoảng cách giữa nút và danh sách sản phẩm */
        transition: all 0.2s ease;
        padding: 10px; /* Thêm padding cho danh sách */
    }

    .product-item {
        display: flex;
        align-items: center;
        padding: 5px 0;
    }

    .product-logo {
        width: 50px; /* Độ rộng của logo */
        height: auto; /* Giữ tỉ lệ */
        margin-right: 10px; /* Khoảng cách giữa logo và tên sản phẩm */
    }

    .product-item:hover {
        background-color: #f1f1f1; /* Hiệu ứng hover */
    }

    /* Định dạng bảng */
    .table th, .table td {
        vertical-align: middle; /* Căn giữa nội dung trong ô */
    }

    /* Thêm hiệu ứng cho nút */
    .btn-info {
        transition: background-color 0.3s;
    }

    .btn-info:hover {
        background-color: #007bff; /* Màu nền khi hover */
        color: white; /* Màu chữ khi hover */
    }
</style>

<script>
    function showProducts(userId) {
        document.getElementById('productList' + userId).style.display = 'block';
    }

    function hideProducts(userId) {
        document.getElementById('productList' + userId).style.display = 'none';
    }
</script>

@endsection
