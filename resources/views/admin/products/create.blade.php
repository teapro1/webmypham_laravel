@extends('layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded">
                <div class="card-header bg-dark text-white text-center py-4">
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-4">
                            <label for="name" class="form-label font-weight-bold">Tên Sản Phẩm:</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="logo" class="form-label font-weight-bold">Logo Sản Phẩm:</label>
                            <input type="file" name="logo" id="logo" class="form-control">
                        </div>

                        <div class="form-group mb-4">
                            <label for="images" class="form-label font-weight-bold">Hình Ảnh Sản Phẩm:</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple>
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="form-label">Mô Tả:</label>
                            <textarea id="description" name="description" class="form-control rounded" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <script src="https://cdn.tiny.cloud/1/shqmnolqswa73k4x7pwb4t9ob85yh2wr4vwhey0o8jr81amq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

                        <script>
                            tinymce.init({
                                selector: 'textarea#description',
                                plugins: 'link image code',
                                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | image link | code',
                                image_title: true,
                                automatic_uploads: true,
                                file_picker_types: 'image',
                                images_upload_url: '/upload/image',
                            });
                        </script>

                        <div class="form-group mb-4">
                            <label for="price" class="form-label font-weight-bold">Giá:</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" placeholder="Nhập giá sản phẩm" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="stock" class="form-label font-weight-bold">Số Lượng:</label>
                            <input type="number" name="stock" id="stock" class="form-control" placeholder="Nhập số lượng" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="category_id" class="form-label font-weight-bold">Danh Mục:</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Chọn Danh Mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill">
                                <i class="fas fa-plus"></i> Thêm Sản Phẩm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
