@extends('layouts.app')

@section('title', 'Thay Đổi Mật Khẩu')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Thay Đổi Mật Khẩu</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.profile.password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Mật Khẩu Hiện Tại</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Mật Khẩu Mới</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>

        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu Mới</label>
            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email của bạn</label>
            @php
                $email = Auth::user()->email;
                $hiddenEmail = '***' . substr($email, strpos($email, '@'));
            @endphp
            <input type="text" class="form-control" id="email" name="email" value="{{ $hiddenEmail }}" disabled>
        </div>

        <button type="button" id="send-verification-code" class="btn btn-primary">Gửi Mã Xác Minh</button>
        <input type="text" class="form-control mt-3" id="verification_code" name="verification_code" placeholder="Nhập mã xác minh" required>

        <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Lưu Thay Đổi
        </button>
    </form>
</div>
<div class="text-center mb-4">
   <a href="{{ route('customer.profile.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
</div>
<script>
    const url = "{{ route('customer.emails.verification-code') }}";

    document.getElementById('send-verification-code').addEventListener('click', function() {
        const email = "{{ Auth::user()->email }}"; // Get the user's email
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Mã xác minh đã được gửi đến email của bạn.');
            } else {
                alert('Có lỗi xảy ra trong quá trình gửi mã xác minh.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi, vui lòng thử lại.');
        });
    });
</script>

@endsection
