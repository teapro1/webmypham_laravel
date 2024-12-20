@extends('layouts.admin')

@section('title', 'Sửa Danh Mục')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card with modern design and shadow -->
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-header bg-dark text-white text-center py-4">
                </div>
                <div class="card-body p-5">
                    <!-- Form with more spacing and larger font sizes -->
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Category name -->
                        <div class="form-group mb-5">
                            <label for="name" class="form-label font-weight-bold" style="font-size: 1.2rem;">Tên Danh Mục:</label>
                            <input type="text" name="name" value="{{ $category->name }}" class="form-control form-control-lg rounded-pill" placeholder="Nhập tên danh mục" required style="font-size: 1.1rem; padding: 10px 20px;">
                        </div>

                        <!-- Logo upload with better spacing and larger image -->
                        <div class="form-group mb-5">
                            <label for="logo" class="form-label font-weight-bold" style="font-size: 1.2rem;">Logo Danh Mục:</label>
                            <input type="file" name="logo" class="form-control-file" accept="image/*" onchange="previewLogo(this)">
                            @if($category->logo)
                                <div class="mt-4 text-center">
                                    <img id="logo-preview" src="{{ asset('storage/' . $category->logo) }}" alt="Logo hiện tại" class="img-thumbnail rounded-circle shadow" style="max-width: 200px; margin-top: 20px;">
                                </div>
                            @endif
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 1.1rem;">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-5 py-2" style="font-size: 1.1rem;">
                                <i class="fas fa-save"></i> Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script to preview uploaded logo -->
<script>
    function previewLogo(input) {
        var file = input.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('logo-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
