@extends('layouts.app')

@section('title', 'Thông Tin Cá Nhân')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center">Thiết Lập Tài Khoản</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('customer.profile.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Họ Tên</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Lưu Thay Đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4 text-center">
        <h4 class="mb-3">Quản Lý Tài Khoản</h4>
        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
            <a href="{{ route('customer.profile.addresses') }}" class="btn btn-outline-primary me-md-2">
                <i class="fas fa-map-marker-alt"></i> Quản Lý Địa Chỉ
            </a>
            <a href="{{ route('customer.profile.orders') }}" class="btn btn-outline-secondary me-md-2">
                <i class="fas fa-shopping-cart"></i> Quản Lý Đơn Hàng
            </a>
            <a href="{{ route('customer.profile.password.change') }}" class="btn btn-outline-warning">
                <i class="fas fa-key"></i> Thay Đổi Mật Khẩu
            </a>
        </div>
    </div>
</div>
@endsection
