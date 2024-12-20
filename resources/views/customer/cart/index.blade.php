@extends('layouts.app')

@section('title', 'Giỏ Hàng')

@section('content')
<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($cartItems) && count($cartItems) > 0)
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Hình Ảnh</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Tổng</th>
                    <th>Thao Tác</th>   
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $item['logo']) }}" alt="{{ $item['name'] }}" style="width: 50px; border-radius: 5px;">
                        </td>
                        <td>{{ number_format($item['price'], 0) }} VNĐ</td>
                        <td>
                            <form action="{{ route('cart.update', $item['rowId']) }}" method="POST" class="d-flex">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['qty'] }}" min="1" class="form-control me-2" style="width: 80px;">
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-sync-alt"></i> Cập Nhật
                                </button>
                            </form>
                        </td>
                        <td>{{ number_format($item['price'] * $item['qty'], 0) }} VNĐ</td>
                        <td>
                            <form action="{{ route('cart.remove', $item['rowId']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <h4>Tổng Cộng: <span class="text-danger">{{ number_format($cartTotal, 0) }} VNĐ</span></h4>
            <a href="{{ route('checkout') }}" class="btn btn-primary">
                <i class="fas fa-credit-card"></i> Thanh Toán
            </a>
        </div>
    @else
        <div class="alert alert-warning text-center mt-4" role="alert">
            Giỏ hàng của bạn hiện tại trống!
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .table img {
        border-radius: 5px;
        transition: transform 0.3s;
    }
    .table img:hover {
        transform: scale(1.1);
    }
    .table th {
        text-align: center;
    }
</style>
@endsection
