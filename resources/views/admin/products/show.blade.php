@extends('layouts.admin')

@section('title', 'Chi Tiết Sản Phẩm')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded border-0">
                <div class="card-header bg-dark text-white text-center py-4">
                    <h4 class="mb-0">Chi Tiết Sản Phẩm</h4>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        @if($product->logo)
                            <img src="{{ asset('storage/' . $product->logo) }}" alt="Logo Sản Phẩm" class="img-fluid rounded mb-3" style="max-width: 150px;">
                        @else
                            <p class="text-danger">Không có logo cho sản phẩm này.</p>
                        @endif
                    </div>
                    <h1 class="text-center mb-4">{{ $product->name }}</h1>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Giá:</label>
                        <p class="font-weight-bold text-success" style="font-size: 1.5rem;">{{ number_format($product->price, 0) }} VNĐ</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Số Lượng:</label>
                        <p>{{ $product->stock }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Danh Mục:</label>
                        <p>{{ $product->category ? $product->category->name : 'Không có danh mục' }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Mô Tả:</label>
                      <div class="product-description">
    {!! $product->description !!}
</div>

                    </div>

                    <div class="mb-4">
                        <label class="form-label font-weight-bold">Hình Ảnh:</label>
                        @if($product->images->isNotEmpty())
                            <div class="d-flex flex-wrap justify-content-center">
                                @foreach($product->images as $image)
                                    <div class="position-relative m-2">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Hình Ảnh Sản Phẩm" class="img-fluid rounded" style="max-width: 150px; transition: transform 0.2s; cursor: pointer;" onclick="this.classList.toggle('zoom')">
                                        <style>
                                            .zoom {
                                                transform: scale(1.2);
                                                z-index: 1;
                                            }
                                        </style>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Không có hình ảnh cho sản phẩm này.</p>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                    <a href="{{ session('previous_url', route('admin.categories.index')) }}" class="btn btn-secondary">Quay lại</a>

                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning rounded-pill">
                            <i class="fas fa-edit"></i> Chỉnh Sửa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
