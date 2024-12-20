@extends('layouts.admin')

@section('title', 'Chi Tiết Danh Mục')

@section('content')
<div class="container my-5">
    <div class="card mb-4 shadow-lg rounded-lg border-0 p-4">
        <h1 class="mb-4 text-center">{{ $category->name }}</h1>

        @if($category->description)
            <p class="text-center text-muted" style="font-size: 1.1rem;">{{ $category->description }}</p>
        @else
            <p class="text-center text-muted">Chưa có mô tả</p>
        @endif

        @if($category->logo)
            <div class="text-center mb-4">
                <img src="{{ asset('storage/' . $category->logo) }}" alt="{{ $category->name }} Logo" class="img-thumbnail" style="max-width: 150px; height: auto; border-radius: 10px; border: 1px solid #ccc; padding: 10px;">
            </div>
        @endif
    </div>

    <h3 class="mb-4 text-center">Sản phẩm trong danh mục này:</h3>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-primary rounded-pill">
                            <i class="fas fa-eye"></i> Xem Chi Tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Không có sản phẩm nào trong danh mục này.</p>
            </div>
        @endforelse
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary rounded-pill">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách danh mục
        </a>
    </div>
</div>
@endsection
