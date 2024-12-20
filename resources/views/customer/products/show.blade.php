@extends('layouts.app')

@section('title', 'Chi Tiết Sản Phẩm')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($product->images as $index => $image)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="d-block w-100 img-fluid product-image">
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="mb-3 font-weight-bold">{{ $product->name }}</h1>
            <p class="text-success h4"><strong>Giá:</strong> {{ number_format($product->price, 0) }} VNĐ</p>
            <p><strong>Số Lượng Còn Lại:</strong> {{ $product->stock }}</p>
            <p><strong>Danh Mục:</strong> {{ $product->category->name ?? 'Chưa phân loại' }}</p>
            <p><strong>Mô Tả:</strong></p>
                              <div class="product-description">
    {!! $product->description !!}
</div>

            <div class="mt-4">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-lg shadow">
                        <i class="fas fa-shopping-cart"></i> Thêm vào Giỏ Hàng
                    </button>
                </form>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg shadow">
                    <i class="fas fa-arrow-left"></i> Quay Lại
                </a>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    .product-image {
        border-radius: 10px;
        border: 2px solid #007bff; /* Khung viền cho ảnh */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s; /* Hiệu ứng khi hover */
    }

    .product-image:hover {
        transform: scale(1.05); /* Phóng to ảnh khi hover */
    }

    h1 {
        font-size: 2rem; /* Tăng kích thước chữ cho tên sản phẩm */
        font-weight: 700; /* Làm đậm chữ */
        color: #333; /* Màu chữ tối cho sự nổi bật */
    }

    .btn {
        transition: background-color 0.3s, transform 0.3s; /* Thêm hiệu ứng khi hover */
        padding: 10px 20px; /* Tăng padding để nút lớn hơn */
        border-radius: 25px; /* Bo góc cho nút */
        font-weight: bold; /* Làm đậm chữ */
    }

    .btn i {
        margin-right: 8px; /* Tạo khoảng cách giữa icon và chữ */
    }

    .btn-primary {
        background-color: #007bff; /* Màu nền chính */
        border-color: #007bff; /* Màu biên chính */
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Màu nền khi hover */
        border-color: #0056b3; /* Màu biên khi hover */
    }

    .btn-secondary {
        background-color: #6c757d; /* Màu nền thứ cấp */
        border-color: #6c757d; /* Màu biên thứ cấp */
    }

    .btn-secondary:hover {
        background-color: #5a6268; /* Màu nền khi hover */
        border-color: #545b62; /* Màu biên khi hover */
    }
</style>
@endsection
@endsection
