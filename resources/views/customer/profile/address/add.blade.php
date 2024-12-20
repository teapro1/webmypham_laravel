@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4"><i class="fas fa-map-marker-alt"></i> Thêm Địa Chỉ Mới</h2>

        <!-- Hiển thị thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Hiển thị lỗi -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.profile.address.add') }}">
            @csrf

            <!-- Tỉnh/Thành phố -->
            <div class="mb-3">
                <label for="province" class="form-label"><i class="fas fa-building"></i> Tỉnh/Thành phố:</label>
                <select id="province" name="province" class="form-select" required>
                    <option value="">Chọn tỉnh/thành phố</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Quận/Huyện -->
            <div class="mb-3">
                <label for="district" class="form-label"><i class="fas fa-city"></i> Quận/Huyện:</label>
                <select id="district" name="district" class="form-select" required disabled>
                    <option value="">Chọn quận/huyện</option>
                </select>
            </div>

            <!-- Xã/Phường -->
            <div class="mb-3">
                <label for="ward" class="form-label"><i class="fas fa-map-pin"></i> Xã/Phường:</label>
                <select id="ward" name="ward" class="form-select" required disabled>
                    <option value="">Chọn xã/phường</option>
                </select>
            </div>

            <!-- Địa chỉ chi tiết -->
            <div class="mb-3">
                <label for="detail_address" class="form-label"><i class="fas fa-home"></i> Địa chỉ chi tiết:</label>
                <input type="text" name="detail_address" id="detail_address" class="form-control" required>
            </div>

            <!-- Nút Lưu -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-save"></i> Thêm địa chỉ
                </button>
                <!-- Nút Quay lại -->
                <a href="{{ route('customer.profile.addresses') }}" class="btn btn-secondary btn-lg">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </form>
    </div>

    <!-- jQuery và Ajax cho dropdown động -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#province').change(function() {
                const provinceId = $(this).val();
                $('#district').prop('disabled', false).empty().append('<option value="">Chọn quận/huyện</option>');
                $('#ward').prop('disabled', true).empty().append('<option value="">Chọn xã/phường</option>');

                if (provinceId) {
                    $.get(`/districts/${provinceId}`, function(data) {
                        if (data.length > 0) {
                            $.each(data, function(index, district) {
                                $('#district').append(`<option value="${district.code}">${district.name}</option>`);
                            });
                        } else {
                            $('#district').append('<option value="">Không có quận/huyện</option>');
                        }
                    }).fail(function() {
                        alert('Có lỗi xảy ra trong quá trình tải quận/huyện.');
                    });
                }
            });

            $('#district').change(function() {
                const districtId = $(this).val();
                $('#ward').prop('disabled', false).empty().append('<option value="">Chọn xã/phường</option>');

                if (districtId) {
                    $.get(`/wards/${districtId}`, function(data) {
                        if (data.length > 0) {
                            $.each(data, function(index, ward) {
                                $('#ward').append(`<option value="${ward.code}">${ward.name}</option>`);
                            });
                        } else {
                            $('#ward').append('<option value="">Không có xã/phường</option>');
                        }
                    }).fail(function() {
                        alert('Có lỗi xảy ra trong quá trình tải xã/phường.');
                    });
                }
            });
        });
    </script>
</div>
@endsection
