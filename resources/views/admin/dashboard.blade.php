@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Trang Quản Lý Admin</h1>
            <p class="lead">Chào mừng bạn đến với trang quản lý dành cho admin.</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-4">
                <a href="{{ route('admin.statistics.index') }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-chart-line"></i> Thống Kê</h5>
                            <p class="card-text">Xem thống kê về doanh thu và lượng sản phẩm.</p>
                            <button class="btn btn-primary">Đi đến Thống Kê</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-th-list"></i> Quản Lý Danh Mục</h5>
                            <p class="card-text">Xem và chỉnh sửa danh mục sản phẩm của bạn.</p>
                            <button class="btn btn-primary">Đi đến Danh Mục</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-box"></i> Quản Lý Sản Phẩm</h5>
                            <p class="card-text">Thêm, sửa hoặc xóa sản phẩm của bạn.</p>
                            <button class="btn btn-primary">Đi đến Sản Phẩm</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mt-4">
                <a href="{{ route('admin.carts.index') }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Quản Lý Giỏ Hàng</h5>
                            <p class="card-text">Quản lý giỏ hàng của khách hàng.</p>
                            <button class="btn btn-primary">Đi đến Giỏ Hàng</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mt-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-receipt"></i> Quản Lý Đơn Hàng</h5>
                            <p class="card-text">Xem và xử lý đơn hàng của khách hàng.</p>
                            <button class="btn btn-primary">Đi đến Đơn Hàng</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <style>
        .container {
            background-color: #f8f9fa; /* Light background for the container */
            padding: 20px;
            border-radius: 8px;
        }

        .card {
            border: none;
            border-radius: 8px;
            transition: transform 0.2s;
            cursor: pointer; /* Change cursor to pointer */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-text {
            color: #6c757d; /* Slightly muted text */
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        /* Center alignment for the cards */
        .row {
            margin-bottom: 20px;
        }
    </style>
@endsection
