<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tiny.cloud/1/shqmnolqswa73k4x7pwb4t9ob85yh2wr4vwhey0o8jr81amq/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://kit.fontawesome.com/a4c00a89bc.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f8f8f8;
        }

        header {
            background-color: #FFB6C1;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex: 1;
        }

        .header-title {
            flex: 1;
            text-align: center;
            margin: 0;
            transition: margin-left 0.3s ease;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #FFC0CB;
            transition: left 0.3s ease;
            z-index: 1000;
            padding-top: 20px; 
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar a {
            color: #fff;
            padding: 15px 20px;
            display: flex;
            align-items: center; 
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #FF69B4;
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 24px;
            color: #FF69B4;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            margin-left: 150px;
            transition: margin-left 0.3s ease;
        }

        .container.active {
            margin-left: 250px;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info span {
            margin-right: 15px;
        }

        header.active .header-title {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
     
    .table-hover tbody tr:hover {
        background-color: #f1f1f1; 
    }
    .table th, .table td {
        vertical-align: middle; 
    }
    .btn {
        margin: 0 5px;
    }
    .container {
        padding: 20px; 
    }

    </style>
</head>
<body>
<header>
    <div class="header-content">
        <span class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></span>
    </div>
    <div class="user-info">
        <span>Hello, {{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
        @if(Auth::check())
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Đăng Xuất</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Đăng Nhập</a>
            <a href="{{ route('register') }}" class="btn btn-success btn-sm">Đăng Ký</a>
        @endif
    </div>
</header>

<div class="sidebar" id="sidebar">
    <a href="{{ route('admin.statistics.index') }}"><i class="fas fa-chart-line"></i>Thống Kê</a>
    <a href="{{ route('categories.index') }}"><i class="fas fa-th-list"></i>Danh Mục</a>
    <a href="{{ route('products.index') }}"><i class="fas fa-box"></i>Sản Phẩm</a>
    <a href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i>Giỏ Hàng</a>
    <a href="{{ route('admin.orders.index') }}"><i class="fas fa-receipt"></i>Đơn Hàng</a>
    <a href="#"><i class="fas fa-ellipsis-h"></i>Khác</a>
</div>

<div class="container" id="main-content">
    @yield('content')
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const header = document.querySelector('header');

        sidebar.classList.toggle('active');
        mainContent.classList.toggle('active');

        // Cập nhật margin-left cho header
        if (sidebar.classList.contains('active')) {
            header.style.marginLeft = '250px';
        } else {
            header.style.marginLeft = '0';
        }
    }
</script>
</body>
</html>
