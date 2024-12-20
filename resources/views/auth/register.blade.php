<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Khách hàng</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: linear-gradient(to right, #6DD5FA, #FFFFFF);
        }
        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #4CAF50;
        }
        .header i {
            font-size: 50px;
            color: #4CAF50;
            margin-bottom: 10px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #fff;
            text-align: center;
        }
        .alert-success {
            background-color: #4CAF50;
        }
        .alert-danger {
            background-color: #f44336;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        .form-group input {
            width: 86.4%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            padding-left: 40px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .form-group i {
            position: absolute;
            top: 38px;
            left: 10px;
            color: #4CAF50;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 12px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .is-invalid {
            border-color: #e3342f;
        }
        .text-danger {
            color: #e3342f;
        }
        /* Ẩn trường mã xác nhận ban đầu */
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-user-plus"></i>
            <h1>Đăng Ký</h1>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Tên</label>
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nhập tên của bạn" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Nhập email của bạn" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <!-- Nút gửi mã xác nhận -->
                <button type="button" id="send-verification-code" class="btn-primary" style="margin-top: 10px;">Gửi mã xác nhận</button>
            </div>

            <!-- Trường nhập mã xác nhận -->
            <div class="form-group hidden" id="verification-code-group">
                <label for="verification_code">Mã xác nhận</label>
                <i class="fas fa-key"></i>
                <input type="text" name="verification_code" id="verification_code" class="form-control" placeholder="Nhập mã xác nhận">
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Nhập mật khẩu" required>
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu" required>
            </div>

            <button type="submit" class="btn-primary">Đăng Ký</button>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>

        <div class="form-footer">
            <p>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
        </div>

        <!-- Thông báo thành công hoặc lỗi -->
        <div id="message" class="alert hidden"></div>
    </div>

    <script>
        // Nút gửi mã xác nhận
        const sendCodeButton = document.getElementById('send-verification-code');
        const verificationCodeGroup = document.getElementById('verification-code-group');
        const messageDiv = document.getElementById('message');
        let countdown;

        sendCodeButton.addEventListener('click', function () {
            const email = document.getElementById('email').value;

            // Gửi yêu cầu gửi mã xác nhận
            fetch("http://localhost:8000/send-verification-code", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Hiển thị thông báo thành công
                messageDiv.classList.remove('hidden');
                messageDiv.textContent = data.message;
                messageDiv.className = 'alert alert-success';

                // Hiển thị trường nhập mã xác nhận
                verificationCodeGroup.classList.remove('hidden');

                // Khởi động đếm ngược 60 giây cho việc gửi mã
                sendCodeButton.disabled = true;
                let timer = 60;
                countdown = setInterval(() => {
                    sendCodeButton.textContent = `Đã gửi (${timer}s)`;
                    timer--;
                    if (timer < 0) {
                        clearInterval(countdown);
                        sendCodeButton.textContent = 'Gửi mã xác nhận lại';
                        sendCodeButton.disabled = false;
                    }
                }, 1000);
            })
            .catch(error => {
                // Hiển thị thông báo lỗi
                messageDiv.classList.remove('hidden');
                messageDiv.textContent = 'Lỗi: ' + error.message;
                messageDiv.className = 'alert alert-danger';
                console.error('Error:', error);
            });
        });

        // Xử lý đăng ký tài khoản
        const registerForm = document.querySelector('form');
        registerForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn chặn gửi form mặc định

            const formData = new FormData(registerForm);
            fetch("http://localhost:8000/register", {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.success);
                    window.location.href = "http://localhost:8000/login"; 
                } else {
                    // Hiển thị thông báo lỗi
                    messageDiv.classList.remove('hidden');
                    messageDiv.textContent = data.message;
                    messageDiv.className = 'alert alert-danger';
                }
            })
            .catch(error => {
                // Hiển thị thông báo lỗi
                messageDiv.classList.remove('hidden');
                messageDiv.textContent = 'Lỗi: ' + error.message;
                messageDiv.className = 'alert alert-danger';
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
