<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
        public function boot(): void
    {
        // Mail "quên mật khẩu": gửi liên kết đặt lại mật khẩu (có hạn sử dụng)
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Đặt lại mật khẩu - Góc Nhìn')
                ->greeting('Xin chào ' . $notifiable->name . ',')
                ->line('Chúng tôi nhận được yêu cầu cấp lại mật khẩu cho tài khoản của bạn.')
                ->action('Đặt lại mật khẩu', $url)
                ->line("Liên kết có hiệu lực trong {$minutes} phút.")
                ->line('Nếu bạn không yêu cầu, hãy bỏ qua email này, mật khẩu của bạn sẽ không thay đổi.');
        });
    }
}
