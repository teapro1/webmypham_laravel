<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Mỹ Phẩm Cao Cấp</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" crossorigin="anonymous">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/a4c00a89bc.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/shqmnolqswa73k4x7pwb4t9ob85yh2wr4vwhey0o8jr81amq/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

  <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f5f5f5;
            padding-top: 190px; 
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        header {
            background-color: #FF69B4; 
            color: #fff;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
            left: 0; 
            right: 0; 
            z-index: 1000; 
        }
       nav {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #FFB6C1;
    padding: 15px;
    border-radius: 5px;
}

nav a {
    color: #fff;
    margin: 0 70px; /* Điều chỉnh khoảng cách ở đây */
    font-weight: bold;
    transition: color 0.3s ease, transform 0.3s ease;
    font-size: 1.2rem;
}

nav a:hover {
    color: #FF69B4;
    transform: scale(1.1);
}

    .search-bar {
    margin: 20px 0;
    text-align: center;
   
}


        .container {
            max-width: 1200px;
            min-height: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        footer {
            background-color: #FFB6C1; 
            color: #fff;
            text-align: center;
            padding: 15px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .product-card {
            margin-bottom: 20px !important;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 350px;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .product-card h5 {
            height: 40px;
            margin: 0;
            font-size: 1.5rem;
        }

        .product-card p {
            height: 30px;
            margin: 0;
            font-size: 1.2rem;
            color: #d9534f;
            font-weight: bold;
        }

        .pagination {
            justify-content: center;
            padding: 15px 0;
        }

        /* Dropdown styles */
  .dropdown-menu {
    border-radius: 8px;
    padding: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    min-width: 200px; 
    margin-top: 5px; 
    z-index: 1050; /* Set a higher z-index */
}


        .dropdown-item {
            display: flex; 
            align-items: center; 
            padding: 10px 15px;
            font-size: 14px; 
            white-space: nowrap;
            transition: background 0.3s ease-in-out;
        }

        .dropdown-item i {
            margin-right: 10px; 
        }

        .dropdown-item:hover {
            background-color: #f8f9fa; 
            color: #333;
        }

        .dropdown-item button {
            all: unset; 
            width: 100%; 
            text-align: left; 
            padding: 10px 15px; 
            display: flex;
            align-items: center;
            font-size: 14px; 
        }

        .dropdown-item button i {
            margin-right: 10px; 
        }

        .btn-secondary.dropdown-toggle {
            background-color: #FF69B4;
            border: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 16px; 
        }

        .btn-secondary.dropdown-toggle:hover {
            background-color: #FF1493;
            color: #fff;
        }

        .dropdown-menu {
            left: auto; 
            transform: none; 
            margin: 0; 
            padding: 0;
        }

        .navbar, .dropdown-menu {
            margin: 0;
            padding: 0;
        }

        .navbar {
            padding-left: 15px; 
        }
    </style>
</head>
<body>
<header>
    <img src="https://theme.hstatic.net/200000551679/1001154878/14/top_banner.jpg?v=2530" alt="Banner" style="width: 100%; height: auto;">
   <nav>
    <a href="{{ route('categories.index') }}">Danh Mục</a>
    <a href="{{ route('products.index') }}">Sản Phẩm</a>
    <a href="{{ route('news.index') }}">Tin Tức</a>
    <a href="{{ route('about.index') }}">Về Chúng Tôi</a>
    <div class="dropdown">
        @if(Auth::check())
            <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Hello, {{ Auth::user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <button class="dropdown-item" onclick="window.location.href='{{ route('customer.profile.index') }}'">
                        <i class="fas fa-user me-2"></i> Thông Tin Cá Nhân
                    </button>
                </li>
                <li>
                    <button class="dropdown-item" onclick="window.location.href='{{ route('cart.index') }}'">
                        <i class="fas fa-shopping-cart me-2"></i> Giỏ Hàng
                    </button>
                </li>
                <li>
                    <button class="dropdown-item" onclick="window.location.href='{{ route('notifications.index') }}'">
                        <i class="fas fa-bell me-2"></i> Thông Báo
                        @php
                            $unpaidVnpayOrdersCount = Auth::user()->orders()
                                ->where('status', 'pending')  
                                ->where('payment_method', 'vnpay') 
                                ->count(); 
                        @endphp
                        @if($unpaidVnpayOrdersCount > 0)
                            <span class="badge bg-danger">{{ $unpaidVnpayOrdersCount }}</span>
                        @endif
                    </button>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; text-align: left;">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất
                        </button>
                    </form>
                </li>
            </ul>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">Đăng Nhập</a>
        @endif
    </div>
</nav>
</header>


<div id="carouselExampleIndicators" class="carousel slide image-slider mt-3" data-bs-ride="carousel" style="width: 64%; margin: 0 auto;">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://theme.hstatic.net/200000551679/1001154878/14/slider_4_master.jpg?v=2530" class="d-block w-100" alt="Image Slider">
        </div>
        <div class="carousel-item">
            <img src="https://theme.hstatic.net/200000551679/1001154878/14/slider_2_master.jpg?v=2530" class="d-block w-100" alt="Image Slider">
        </div>
        <div class="carousel-item">
            <img src="https://theme.hstatic.net/200000551679/1001154878/14/slider_3_master.jpg?v=2530" class="d-block w-100" alt="Image Slider">
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
<div class="search-bar">
    <form action="{{ route('products.index') }}" method="GET" class="d-flex justify-content-center mt-3">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..." required style="width: 300px;">
        <button type="submit" class="btn btn-primary">Tìm Kiếm</button>
    </form>
</div>
<div class="container mt-4">
    @yield('content')
</div>

<footer>
    <p>© 2024 Mỹ Phẩm Cao Cấp by Trà Trịnh Sơn </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
