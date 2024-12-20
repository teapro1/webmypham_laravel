@extends('layouts.app')

@section('title', 'Xin Chào')

@section('content')
<div class="container mt-5">
    <div class="row">
        @if($products->count())
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card product-card shadow-sm">
                        <a href="{{ route('products.show', $product->id) }}" class="product-link">
                            <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" class="card-img-top">
                            <div class="card-body text-center">
                                <h3 class="card-title">{{ $product->name }}</h3>
                                <p class="price">Giá: {{ number_format($product->price, 0) }} VNĐ</p>
                            </div>
                        </a>
                        <div class="card-footer text-center">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                <p>Hiện tại không có sản phẩm nào.</p>
            </div>
        @endif
    </div>

    <div class="row">
    <div class="col-12 text-center">
        {{ $products->links() }} {{-- Hiển thị phân trang với thiết kế mới --}}
    </div>
</div>


@endsection

@section('styles')
<style>
    .product-card {
        border-radius: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        text-decoration: none;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .price {
        font-size: 1.2rem;
        font-weight: bold;
        color: #28a745;
    }

    .card-img-top {
        height: 200px; /* Đặt chiều cao cố định */
        object-fit: cover; /* Đảm bảo hình ảnh không bị méo */
    }

    .product-link {
        text-decoration: none !important;
        color: inherit;
        display: block;
    }

    .pagination {
        justify-content: center; /* Canh giữa các nút phân trang */
        margin-top: 20px; /* Khoảng cách trên */
    }

    .pagination .page-link {
        border-radius: 50%; /* Bo tròn các nút */
        margin: 0 5px; /* Khoảng cách giữa các nút */
        background-color: #FF69B4; /* Màu nền */
        color: white; /* Màu chữ */
        transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
        padding: 10px 15px; /* Thêm padding cho các nút */
    }

    .pagination .page-link:hover {
        background-color: #FF1493; /* Màu nền khi hover */
    }

    .pagination .page-item.active .page-link {
        background-color: #28a745; /* Màu nền cho trang hiện tại */
        color: white; /* Màu chữ cho trang hiện tại */
    }

    .pagination .page-item.disabled .page-link {
        background-color: #e9ecef; /* Màu nền cho trang không thể nhấn */
        color: #6c757d; /* Màu chữ cho trang không thể nhấn */
    }
    
    /* Phân trang */
    .pagination {
        justify-content: center; /* Canh giữa các liên kết */
        margin-top: 20px; /* Khoảng cách phía trên */
        display: flex; /* Hiển thị dưới dạng flex */
        flex-wrap: wrap; /* Để linh hoạt trong việc xếp hàng */
    }

    .pagination .page-link {
        border-radius: 50%; /* Bo tròn các nút */
        margin: 0 5px; /* Khoảng cách giữa các nút */
        background-color: #007bff; /* Màu nền */
        color: white; /* Màu chữ */
        transition: background-color 0.3s; /* Hiệu ứng chuyển màu */
        padding: 8px 12px; /* Kích thước nút */
        font-size: 14px; /* Kích thước chữ */
    }

    .pagination .page-link:hover {
        background-color: #0056b3; /* Màu nền khi hover */
    }

    .pagination .page-item.active .page-link {
        background-color: #28a745; /* Màu nền cho trang hiện tại */
        color: white; /* Màu chữ cho trang hiện tại */
    }

    .pagination .page-item.disabled .page-link {
        background-color: #e9ecef; /* Màu nền cho trang không thể nhấn */
        color: #6c757d; /* Màu chữ cho trang không thể nhấn */
    }

</style>
@endsection
