@extends('layouts.admin')

@section('title', 'Danh Sách Sản Phẩm')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg rounded-pill px-4">
            <i class="fas fa-plus me-2"></i> Thêm Sản Phẩm Mới
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
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Danh Mục</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($products as $product)
                    <tr>
                        <td>
                            @if($product->logo)
                                <img src="{{ asset('storage/' . $product->logo) }}" alt="Logo" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                            @else
                                <img src="{{ asset('path/to/default-image.png') }}" alt="No Image" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px;">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>{{ number_format($product->price, 0) }} VNĐ</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            {{ $product->category ? $product->category->name : 'Không có danh mục' }}
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Actions">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info mx-1">
                                    <i class="fas fa-eye"></i> Chi Tiết
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning mx-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này không?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger mx-1">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Hiển thị phân trang nếu có -->
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection
