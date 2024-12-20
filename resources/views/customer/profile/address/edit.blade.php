@extends('layouts.app') <!-- Điều chỉnh theo layout của bạn -->

@section('content')
<div class="container mt-5 mb-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="fas fa-edit"></i> Chỉnh Sửa Địa Chỉ</h2>
        </div>
        <div class="card-body p-4">
            <!-- Hiển thị thông báo thành công -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Hiển thị lỗi -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.profile.address.update', $address->id) }}">
                @csrf
                @method('PUT') 
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="province" class="form-label"><i class="fas fa-map-marker-alt"></i> Tỉnh/Thành phố:</label>
                            <select id="province" name="province" class="form-select" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province['code'] }}" {{ $province['code'] == $address->province ? 'selected' : '' }}>
                                        {{ $province['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="district" class="form-label"><i class="fas fa-city"></i> Quận/Huyện:</label>
                            <select id="district" name="district" class="form-select" required disabled>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ward" class="form-label"><i class="fas fa-map-pin"></i> Xã/Phường:</label>
                            <select id="ward" name="ward" class="form-select" required disabled>
                                <option value="">Chọn xã/phường</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="detail_address" class="form-label"><i class="fas fa-home"></i> Địa chỉ chi tiết:</label>
                            <input type="text" name="detail_address" id="detail_address" class="form-control" value="{{ old('detail_address', $address->detail_address) }}" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Cập nhật địa chỉ
                    </button>
                    <a href="{{ route('customer.profile.addresses') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sử dụng JQuery và xử lý chọn tỉnh/quận/huyện -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const initialDistrictId = "{{ $address->district }}"; // ID quận hiện tại
        const initialWardId = "{{ $address->ward }}"; // ID xã hiện tại

        // Khi chọn tỉnh
        $('#province').change(function() {
            const provinceId = $(this).val();
            $('#district').prop('disabled', false).empty().append('<option value="">Chọn quận/huyện</option>');
            $('#ward').prop('disabled', true).empty().append('<option value="">Chọn xã/phường</option>');

            if (provinceId) {
                $.get(`/districts/${provinceId}`, function(data) {
                    if (data.length > 0) {
                        $.each(data, function(index, district) {
                            $('#district').append(`<option value="${district.code}" ${district.code == initialDistrictId ? 'selected' : ''}>${district.name}</option>`);
                        });
                        if (initialDistrictId) {
                            $('#district').trigger('change');
                        }
                    } else {
                        $('#district').append('<option value="">Không có quận/huyện</option>');
                    }
                }).fail(function() {
                    alert('Có lỗi xảy ra trong quá trình tải quận/huyện.');
                });
            }
        });

        // Khi chọn quận
        $('#district').change(function() {
            const districtId = $(this).val();
            $('#ward').prop('disabled', false).empty().append('<option value="">Chọn xã/phường</option>');

            if (districtId) {
                $.get(`/wards/${districtId}`, function(data) {
                    if (data.length > 0) {
                        $.each(data, function(index, ward) {
                            $('#ward').append(`<option value="${ward.code}" ${ward.code == initialWardId ? 'selected' : ''}>${ward.name}</option>`);
                        });
                    } else {
                        $('#ward').append('<option value="">Không có xã/phường</option>');
                    }
                }).fail(function() {
                    alert('Có lỗi xảy ra trong quá trình tải xã/phường.');
                });
            }
        });

        // Tự động chọn tỉnh và quận/huyện dựa trên dữ liệu hiện tại
        $('#province').val("{{ $address->province }}").trigger('change');
    });
</script>
@endsection
