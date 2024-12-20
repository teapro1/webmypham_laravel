@extends('layouts.app')

@section('title', 'Danh Mục')

@section('content')
<div class="container mt-5">
    <!-- Categories Grid -->
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card category-card shadow-sm h-100 position-relative d-flex flex-column">
                    <a href="{{ route('products.byCategory', $category->id) }}" class="category-link">
                        <img src="{{ asset('storage/' . $category->logo) }}" alt="{{ $category->name }}" class="card-img-top category-logo">
                        <div class="card-body text-center d-flex flex-column justify-content-between" style="height: 200px;">
                            <h3 class="category-title">{{ $category->name }}</h3>
                            <p class="category-description">Số sản phẩm có trong danh mục: {{ $category->products_count }}</p>
                        </div>
                    </a>
                    <div class="card-footer text-center">
                        <a href="{{ route('products.byCategory', $category->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem Chi Tiết
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="custom-pagination my-2">
    <div class="contai text-center">
        {{ $categories->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>  

@endsection

@section('styles')
<style>
    .bg-white {
        background-color: transparent !important;
    }
    .my-2 {
        margin: 0 !important;
        height: auto !important;
    }
    
    .category-card, .product-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 10px;
        overflow: hidden;
        background-color: #ffffff;
        border: none;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        min-height: 300px;
    }
    
    .card-body {
        flex-grow: 1;
    }

    .category-link {
        text-decoration: none;
        color: inherit;
    }

    .category-logo {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: opacity 0.3s ease-in-out;
    }

    .category-title {
        margin-top: 10px;
        font-size: 1.5rem;
        font-weight: bold;
        color: #007BFF;
        margin-bottom: 0.5rem;
    }

    .category-description {
        font-size: 1rem;
        color: #555;
    }

    .category-card:hover .category-logo {
        opacity: 0.9;
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .btn {
        margin-top: 10px;
        display: inline-block;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .card-footer {
        background-color: transparent;
        border-top: none;
        margin-top: auto; /* Đẩy footer xuống dưới cùng */
        display: flex; /* Thiết lập display flex */
        justify-content: center; /* Căn giữa nội dung */
        width: 100%; /* Đảm bảo footer chiếm toàn bộ chiều rộng */
        text-align: center; /* Căn giữa văn bản */
    }

    h1 {
        font-size: 2.5rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #343a40;
    }
</style>
@endsection  
