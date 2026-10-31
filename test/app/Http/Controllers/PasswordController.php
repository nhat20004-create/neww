<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordController extends Controller
{
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(
            ['email' => ['required', 'email']],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
            ]
        );

        try {
            Password::sendResetLink($request->only('email'));
        } catch (\Throwable $e) {
            Log::error('Gửi mail đặt lại mật khẩu thất bại', ['error' => $e->getMessage()]);
        }

        Log::channel('auth')->info('forgot_password_requested', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        // Luôn trả cùng một thông báo
        return back()->with('success', 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi hướng dẫn đặt lại mật khẩu.');
    }

    // Đặt lại mật khẩu
    public function showReset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate(
            [
                'token' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed', PasswordRule::min(8)],
            ],
            [
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
                'password.required' => 'Vui lòng nhập mật khẩu mới.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $user->forceFill([
                    'password' => $password, // cast 'hashed'
                    'remember_token' => Str::random(60),
                ])->save();

                Log::channel('auth')->info('password_changed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'via' => 'reset',
                    'ip' => $request->ip(),
                ]);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Liên kết không hợp lệ hoặc đã hết hạn.']);
        }

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công, hãy đăng nhập.');
    }

    // Đổi mật khẩu khi đang đăng nhập

    public function showChange()
    {
        return view('auth.change-password');
    }

    public function change(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->forceFill(['password' => $request->password])->save();

        Log::channel('auth')->info('password_changed', [
            'user_id' => $user->id,
            'email' => $user->email,
            'via' => 'change',
            'ip' => $request->ip(),
        ]);

        return redirect()->route('blog')->with('success', 'Đổi mật khẩu thành công.');
    }
}
