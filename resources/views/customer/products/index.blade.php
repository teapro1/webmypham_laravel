@extends('layouts.app')

@section('title', 'Danh Sách Sản Phẩm')

@section('content')

<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="title"></h2>
    
    <form action="{{ isset($category) ? route('products.byCategory', ['category' => $category->id]) : route('products.index') }}" method="GET" class="ml-auto" style="display: inline-block;">
        <select name="sort" onchange="this.form.submit()" class="form-control" style="display: inline-block; width: auto;">
            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao đến thấp</option>
        </select>
    </form>
</div>


    <div class="row">
        @if(session('message'))
            <div class="col-12">
                <div class="alert alert-info text-center">
                    {{ session('message') }}
                </div>
            </div>
        @endif

        {{-- Thông báo số lượng sản phẩm tìm thấy --}}
        @if(isset($search) && $productCount > 0)
            <div class="col-12 text-center mb-3">
                <p>Đã tìm thấy {{ $productCount }} sản phẩm cho từ khóa "{{ $search }}".</p>
            </div>
        @elseif(isset($search))
            <div class="col-12 text-center mb-3">
                <p>Không có sản phẩm nào phù hợp với từ khóa "{{ $search }}".</p>
            </div>
        @endif

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
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Chi Tiết
                            </a>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-shopping-cart"></i> Thêm vào Giỏ
                                </button>
                            </form>
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


<div class="custom-pagination my-2"> 
    <div class="contai text-center">
        {{ $products->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>


<style>
    .card-title {
    font-size: 1.2rem;
}

.price {
    font-size: 1rem; 
}

.bg-white {
    background-color: transparent !important;
}
.contai text-center {
    max-width: 1200px;
    min-height: 800px;
    margin: 20px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.custom-pagination {
    margin-top: 0; 
    width: 100%;
    justify-content: center; 
    align-items: center; 
}

.custom-pagination .page-item {
    margin: 0 2px; 
}

.custom-pagination .page-link {
    padding: 2px 4px !important; 
    font-size: 0.75rem !important; 
    border-radius: 3px !important; 
    width: 60px !important; 
    height: 30px !important; 
    display: flex !important; 
    align-items: center !important; 
    justify-content: center !important; 
    transition: background-color 0.3s !important; 
    color: #000 !important; 
    background-color: #fff !important; 
    box-sizing: border-box; 
}

.custom-pagination .page-link:hover {
    background-color: #ff69b4 !important; 
    color: #000 !important; 
}

.custom-pagination .active .page-link {
    background-color: #ff69b4 !important; 
    color: #fff !important; 
}

.custom-pagination .page-item.disabled .page-link {
    color: #ccc !important; 
    background-color: transparent !important; 
    pointer-events: none !important; 
}

.my-2 { 
    margin: 0 !important; 
    height: auto !important; 
}

.py-2 {
    padding-top: 0.1rem !important; 
    padding-bottom: 0.1rem !important; 
}

.px-4 {
    padding-right: 0.5rem !important; 
    padding-left: 0.5rem !important; 
}
</style>

@endsection
