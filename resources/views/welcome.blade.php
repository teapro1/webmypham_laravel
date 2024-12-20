@extends('layouts.app')

@section('title', 'Hệ Thống Mỹ Phẩm Cao Cấp')

@section('content')
<div class="container mt-5">
  
    <!-- Product Section -->
    <div class="product-section">
        <h2 class="mb-4">Sản Phẩm Nổi Bật</h2>
        <div class="row">
            @if($products->count())
                @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card product-card shadow-sm">
                            <img src="{{ asset('storage/' . $product->logo) }}" alt="{{ $product->name }}" class="card-img-top">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $product->name }}</h5>
                        
                                <p class="price">Giá: {{ number_format($product->price, 0) }} VNĐ</p>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">
                                    <i class="fas fa-info-circle"></i> Xem Chi Tiết
                                </a>
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
    </div>
</div>
@endsection

@section('styles')
<style>
    .product-card {
        border-radius: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .price {
        font-size: 1.5rem;
        font-weight: bold;
        color: #28a745;
    }

    .btn-primary, .btn-success {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .cart-section {
        margin-bottom: 50px;
    }
</style>
@endsection

@section('scripts')
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
