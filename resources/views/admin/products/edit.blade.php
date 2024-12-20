@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Sản Phẩm')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded border-0">
                <div class="card-header bg-dark text-white text-center">
                    <h4 class="mb-0">Chỉnh Sửa Sản Phẩm</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label for="name" class="form-label">Tên Sản Phẩm:</label>
                            <input type="text" id="name" name="name" class="form-control rounded-pill" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="logo" class="form-label">Logo:</label>
                            <input type="file" id="logo" name="logo" class="form-control rounded-pill">
                            @error('logo')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @if($product->logo)
                                <div class="mt-2">
                                    <label>Hình Ảnh Hiện Tại:</label>
                                    <img src="{{ asset('storage/' . $product->logo) }}" alt="Logo" class="img-fluid rounded" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="form-label">Mô Tả:</label>
                            <textarea id="description" name="description" class="form-control rounded" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                       <script src="https://cdn.tiny.cloud/1/shqmnolqswa73k4x7pwb4t9ob85yh2wr4vwhey0o8jr81amq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

                        <script>
                            tinymce.init({
                            selector: 'textarea#description',  // Chọn textarea của bạn
                            plugins: 'link image code',        // Các plugin bạn cần
                            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | image link | code',
                            image_title: true,
                            automatic_uploads: true,
                            file_picker_types: 'image',
                            images_upload_url: '/upload/image', 
                            // Các tùy chọn khác bạn muốn
                            });
                        </script>

                        <div class="form-group mb-4">
                            <label for="price" class="form-label">Giá:</label>
                            <input type="number" id="price" name="price" class="form-control rounded-pill" value="{{ old('price', $product->price) }}" step="0.01" required>
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="stock" class="form-label">Số Lượng:</label>
                            <input type="number" id="stock" name="stock" class="form-control rounded-pill" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="category_id" class="form-label">Danh Mục:</label>
                            <select id="category_id" name="category_id" class="form-control rounded-pill" required>
                                <option value="">Chọn Danh Mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="images" class="form-label">Thêm Hình Ảnh Sản Phẩm:</label>
                            <input type="file" id="images" name="images[]" class="form-control rounded-pill" multiple>
                            @error('images.*')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Hình Ảnh Sản Phẩm:</label>
                            @if($product->images->isNotEmpty())
                                <div class="row">
                                    @foreach($product->images as $image)
                                        <div class="col-4 mb-2">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Hình Ảnh" class="img-fluid rounded" style="max-width: 100%;">
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>Không có hình ảnh cho sản phẩm này.</p>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success rounded-pill">
                                <i class="fas fa-save"></i> Cập Nhật
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .form-label {
        font-weight: 500;
    }

    .text-danger {
        font-size: 0.9rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem; /* Khoảng cách giữa các phần */
    }
</style>
@endsection
