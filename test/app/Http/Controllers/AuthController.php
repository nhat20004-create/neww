<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // cast 'hashed' trong User
        ]);

        // Ghi log thời gian đăng ký (thời gian tự có trong mỗi dòng log)
        Log::channel('auth')->info('register', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        // Gửi mail chào mừng; lỗi SMTP không được làm hỏng việc đăng ký
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Throwable $e) {
            Log::error('Gửi mail chào mừng thất bại', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('blog')->with('success', 'Đăng ký thành công.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $ok = Auth::attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        );

        if (!$ok) {
            Log::channel('auth')->warning('login_failed', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return back()
                ->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Ghi log thời gian đăng nhập (không ghi mật khẩu / toàn bộ object user)
        Log::channel('auth')->info('login', [
            'user_id' => Auth::id(),
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        return redirect()->intended(route('blog'))->with('success', 'Đăng nhập thành công.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('blog')->with('success', 'Bạn đã đăng xuất.');
    }
}
