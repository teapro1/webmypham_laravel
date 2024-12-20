@extends('layouts.admin')

@section('title', 'Danh Sách Danh Mục')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg rounded-pill px-4">
            <i class="fas fa-plus me-2"></i> Thêm Mới Danh Mục
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle shadow-sm">
            <thead class="table-dark text-center">
                <tr>
                    <th>Logo</th>
                    <th>Tên</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($categories as $category)
                    <tr>
                      
                        <!-- Logo -->
                        <td>
                            @if($category->logo)
                                <img src="{{ asset('storage/' . $category->logo) }}" alt="Logo" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                            @else
                                <img src="{{ asset('path/to/default-image.png') }}" alt="No Image" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                            @endif
                        </td>

                        <!-- Tên Danh Mục -->
                        <td>
                            <a href="{{ route('products.byCategory', $category->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $category->name }}
                            </a>
                        </td>

                        <!-- Hành Động -->
                        <td>
                            <div class="btn-group" role="group" aria-label="Actions">
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-info mx-1">
                                    <i class="fas fa-eye"></i> Chi Tiết
                                </a>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning mx-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá danh mục này không?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mx-1">
                                        <i class="fas fa-trash-alt"></i> Xoá
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
