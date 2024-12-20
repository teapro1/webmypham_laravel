@extends('layouts.app')

@section('title', 'Thanh Toán')

@section('content')
<div class="container mt-5">
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form id="paymentForm" action="{{ route('checkout.process') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf
        <h4 class="mb-4"><i class="fas fa-credit-card"></i> Thông tin thanh toán</h4>

        <div class="form-group mb-3"> 
            <label for="name"><i class="fas fa-user"></i> Tên người nhận</label>
            <input type="text" name="name" id="name" class="form-control" required placeholder="Nhập tên người nhận">
        </div>

        <div class="form-group mb-3"> 
            <label for="phone"><i class="fas fa-phone"></i> Số điện thoại</label>
            <input type="text" name="phone" id="phone" class="form-control" required placeholder="Nhập số điện thoại người nhận">
        </div>

        <div class="form-group mb-3"> 
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" name="email" id="email" class="form-control" required placeholder="Nhập email người nhận">
        </div>

        <h4 class="mt-4"><i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng</h4>
        <div class="form-group">
            <label for="selected_address">Chọn địa chỉ giao hàng:</label>
            <select name="selected_address" id="selected_address" class="form-control" required>
                @foreach ($addresses as $address)
                    <option value="{{ $address['id'] }}">
                        {{ $address['detail_address'] }}, {{ $address['ward_name'] }}, {{ $address['district_name'] }}, {{ $address['province_name'] }}
                    </option>
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('address_id') }}</span>
        </div>

        <a href="{{ route('customer.profile.address.add') }}" class="btn btn-link p-0">Thêm địa chỉ mới</a>

        <h4 class="mt-4"><i class="fas fa-credit-card"></i> Phương thức thanh toán</h4>
        <div class="form-group mb-4">
            <label for="payment_method"><i class="fas fa-money-bill-wave"></i> Chọn phương thức thanh toán</label>
            <select id="payment_method" name="payment_method" class="form-control" required>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="vnpay">Thanh toán qua VNPAY</option>
                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                <option value="qr_code">Quét mã QR</option>
            </select>
        </div>

        <button id="confirmPayment" type="submit" class="btn btn-primary btn-block mt-4">
            <i class="fas fa-check"></i> Xác nhận thanh toán
        </button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
// Your existing jQuery code for fetching provinces, districts, and wards remains unchanged
$(document).ready(function() {
    // Lấy danh sách tỉnh khi tải trang
    $.ajax({
        url: 'https://provinces.open-api.vn/api/p/',
        method: 'GET',
        success: function(data) {
            let provinceSelect = $('#province');
            data.forEach(function(province) {
                provinceSelect.append(`<option value="${province.code}">${province.name}</option>`);
            });
        }
    });

    // Fetch districts and wards
    $('#province').change(function() {
        let provinceCode = $(this).val();
        $('#district').empty().append('<option value="">Chọn Quận/Huyện</option>');
        $('#ward').empty().append('<option value="">Chọn Xã/Phường</option>');
        if (provinceCode) {
            $.ajax({
                url: `https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`,
                method: 'GET',
                success: function(data) {
                    let districtSelect = $('#district');
                    data.districts.forEach(function(district) {
                        districtSelect.append(`<option value="${district.code}">${district.name}</option>`);
                    });
                    $('#district').prop('disabled', false);
                }
            });
        } else {
            $('#district').prop('disabled', true);
            $('#ward').prop('disabled', true);
        }
    });

    $('#district').change(function() {
        let districtCode = $(this).val();
        $('#ward').empty().append('<option value="">Chọn Xã/Phường</option>');
        if (districtCode) {
            $.ajax({
                url: `https://provinces.open-api.vn/api/d/${districtCode}?depth=2`,
                method: 'GET',
                success: function(data) {
                    let wardSelect = $('#ward');
                    data.wards.forEach(function(ward) {
                        wardSelect.append(`<option value="${ward.code}">${ward.name}</option>`);
                    });
                    $('#ward').prop('disabled', false);
                }
            });
        } else {
            $('#ward').prop('disabled', true);
        }
    });
});
</script>

<style>
    /* Cải thiện kiểu dáng cho form */
    .form-control {
        transition: border-color 0.3s ease;
        border-radius: 0.5rem; /* Bo góc */
    }

    .form-control:focus {
        border-color: #007bff; /* Màu viền khi focus */
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Hiệu ứng shadow */
    }

    /* Định dạng cho nút */
    #confirmPayment {
        transition: background-color 0.3s, transform 0.2s;
        background-color: #007bff; /* Màu nền chính */
        border: none; /* Bỏ viền */
        border-radius: 25px; /* Bo góc */
        font-weight: bold; /* In đậm chữ */
    }

    #confirmPayment:hover {
        background-color: #0056b3; /* Màu nền khi hover */
        transform: translateY(-2px); /* Hiệu ứng nhấc lên khi hover */
    }

    /* Cải thiện kiểu dáng cho alert */
    .alert {
        border-radius: 10px; /* Bo góc */
        margin-bottom: 20px; /* Khoảng cách giữa các alert */
        font-weight: bold; /* In đậm chữ */
        display: flex; /* Flexbox để căn chỉnh icon */
        align-items: center; /* Căn giữa icon và chữ */
    }

    .alert i {
        margin-right: 10px; /* Khoảng cách giữa icon và chữ */
    }

    h4 {
        font-weight: bold; /* In đậm tiêu đề */
        color: #343a40; /* Màu chữ */
    }

    h2 {
        color: #007bff; /* Màu chữ tiêu đề chính */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container {
            padding: 10px; /* Giảm padding cho mobile */
        }

        #confirmPayment {
            font-size: 1rem; /* Tăng font-size cho nút trên mobile */
        }
    }
</style>
@endsection
