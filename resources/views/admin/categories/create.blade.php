@extends('layouts.admin')

@section('title', 'Tạo Danh Mục')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-header bg-dark text-white text-center py-4">
              
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Tên Danh Mục -->
                        <div class="form-group mb-5">
                            <label for="name" class="form-label font-weight-bold" style="font-size: 1.2rem;">Tên Danh Mục:</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg rounded-pill" placeholder="Nhập tên danh mục" required style="font-size: 1.1rem; padding: 10px 20px;">
                        </div>

                        <!-- Logo Danh Mục -->
                        <div class="form-group mb-5">
                            <label for="logo" class="form-label font-weight-bold" style="font-size: 1.2rem;">Logo Danh Mục:</label>
                            <input type="file" name="logo" id="logo" class="form-control-file" accept="image/*" onchange="previewLogo(this)">
                            <div id="logoPreview" class="mt-3" style="display: none;">
                                <img id="previewImg" src="#" alt="Logo Preview" class="img-thumbnail" style="max-width: 100%; border-radius: 10px;">
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2" style="font-size: 1.1rem;">
                                <i class="fas fa-arrow-left"></i> Quay Lại
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-5 py-2" style="font-size: 1.1rem;">
                                <i class="fas fa-plus"></i> Thêm Mới
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
        const file = input.files[0];
        const previewContainer = document.getElementById('logoPreview');
        const previewImg = document.getElementById('previewImg');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    }
</script>
@endsection
