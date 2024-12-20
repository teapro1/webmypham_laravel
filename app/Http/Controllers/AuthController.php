<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache; 
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
  
        Log::info($request->all());
        
   
        $this->validateRegistration($request);
        
  
        Log::info('Thông tin đăng ký:', [
            'name' => $request->name,
            'email' => $request->email,
            'verification_code' => $request->verification_code,
        ]);
        
        if ($request->verification_code != session('verification_code')) {
            Log::warning('Mã xác minh không hợp lệ:', [
                'received_code' => $request->verification_code,
                'expected_code' => session('verification_code'),
            ]);
            return response()->json(['error' => 'Mã xác minh không hợp lệ!'], 400);
        }
        
        return $this->createUser($request);
    }
    
    // Validate 
    protected function validateRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'verification_code' => 'required|integer',
        ], $this->validationMessages());
    }

    protected function validationMessages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được đăng ký.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'verification_code.required' => 'Vui lòng nhập mã xác minh.',
        ];
    }

    protected function sendVerificationCodeToEmail($email)
    {
        $verificationCode = rand(100000, 999999);
        session(['verification_code' => $verificationCode]);

        try {
            Mail::to($email)->send(new VerificationCodeMail($verificationCode));
            Log::info('Mã xác minh đã được gửi đến email: ' . $email);
            return $verificationCode;
        } catch (\Exception $e) {
            Log::error('Gửi mã xác minh không thành công: ' . $e->getMessage());
            return null; 
        }
    }

    // register
    protected function createUser(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            Log::info('Tài khoản người dùng đã được tạo thành công:', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
    
            return response()->json(['success' => 'Tạo tài khoản thành công!'], 201);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo tài khoản người dùng:', [
                'message' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra!'], 500);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
  // log
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ], $this->loginValidationMessages());

    $key = 'login.' . $request->ip();
    if (RateLimiter::tooManyAttempts($key, 5)) {
        return response()->json(['message' => 'Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau vài phút.'], 429);
    }

    if (Auth::attempt($request->only('email', 'password'))) {
        RateLimiter::clear($key); 
        $request->session()->regenerate();
        return redirect()->intended(Auth::user()->role === 'admin' ? route('admin.dashboard') : route('customer.dashboard'));
    }


    RateLimiter::hit($key); 
    throw ValidationException::withMessages([
        'email' => ['Email hoặc mật khẩu không chính xác. Vui lòng kiểm tra lại!'],
    ]);
}

protected function loginValidationMessages()
{
    return [
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không hợp lệ.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
    ];
}

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
// reset pass
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', __($response))
            : back()->withErrors(['email' => __($response)]);
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset')->with(['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $response = Password::reset($request->only('email', 'password', 'token'), function (User $user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            Auth::login($user);
        });

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('home') 
            : back()->withErrors(['email' => [__($response)]]);
    }

    // gui email xac minh
    public function sendVerificationCode(Request $request)
    {
        Log::info('Bắt đầu gửi mã xác minh');
        
        $request->validate(['email' => 'required|email']);
    
        $email = $request->email;
        $cacheKey = 'send_verification_code_' . $email;
    
        if (Cache::has($cacheKey)) {
            $lastSentTime = Cache::get($cacheKey);
            $now = Carbon::now();
    
            if ($now->diffInSeconds($lastSentTime) < 60) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Vui lòng chờ 60 giây trước khi gửi lại mã.'
                ]);
            }
        }
    
        $verificationCode = rand(100000, 999999);
        session(['verification_code' => $verificationCode, 'verification_code_email' => $email]);
        Log::info('Mã xác minh được tạo: ' . $verificationCode);
    
        try {
            Mail::to($email)->send(new VerificationCodeMail($verificationCode));
    
            Cache::put($cacheKey, Carbon::now(), 60);
    
            Log::info('Mã xác minh đã được gửi đến email: ' . $email);
            return response()->json(['success' => true, 'message' => 'Mã xác minh đã được gửi đến email của bạn.']);
        
        } catch (\Exception $e) {
            Log::error('Gửi mã xác minh không thành công: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gửi mã xác minh thất bại. Vui lòng thử lại.']);
        }
    }
}
