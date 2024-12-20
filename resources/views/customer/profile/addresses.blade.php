@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-map-marker-alt"></i> Danh Sách Địa Chỉ</h1>

    <!-- Địa chỉ mặc định -->
    @if($defaultAddress)
        <div class="card border-info mb-4">
            <div class="card-body">
                <h5 class="card-title text-info"><i class="fas fa-map-marker-alt"></i> Địa chỉ mặc định</h5>
                <p class="card-text">
                    {{ $defaultAddress['detail_address'] }}, {{ $defaultAddress['ward_name'] }}, {{ $defaultAddress['district_name'] }}, {{ $defaultAddress['province_name'] }}
                </p>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <p>Không có địa chỉ mặc định.</p>
        </div>
    @endif

    <!-- Danh sách địa chỉ -->
    @if($addresses->isEmpty())
        <p class="text-muted">Chưa có địa chỉ nào được thêm.</p>
    @else
        <div class="row">
            @foreach($addresses as $address)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-map-marker-alt"></i> Địa chỉ: {{ $address['detail_address'] }}</h5>
                            <p class="card-text">
                                <strong>Xã/Phường:</strong> {{ $address['ward_name'] }}<br>
                                <strong>Quận/Huyện:</strong> {{ $address['district_name'] }}<br>
                                <strong>Thành phố:</strong> {{ $address['province_name'] }}
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('customer.profile.address.edit', $address['id']) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('customer.profile.address.delete', $address['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                                @if(!$address['is_default'])
                                    <form action="{{ route('customer.profile.address.setDefault', $address['id']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-star"></i> Đặt làm mặc định
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Nút thêm địa chỉ mới với icon -->
    <div class="d-flex justify-content-between">
        <a href="{{ route('customer.profile.address.add') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Thêm địa chỉ mới
        </a>
        <!-- Nút quay lại với icon -->
        <a href="{{ route('customer.profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>
@endsection
