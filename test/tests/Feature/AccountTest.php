<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private string $authLog;

    protected function setUp(): void
    {
        parent::setUp();

        // Ghi log 'auth' ra file tạm để kiểm tra nội dung
        $this->authLog = storage_path('logs/auth-test.log');
        @unlink($this->authLog);
        config(['logging.channels.auth' => [
            'driver' => 'single',
            'path' => $this->authLog,
            'level' => 'info',
        ]]);
    }

    protected function tearDown(): void
    {
        @unlink($this->authLog);
        parent::tearDown();
    }

    private function logContents(): string
    {
        return is_file($this->authLog) ? file_get_contents($this->authLog) : '';
    }

    public function test_register_sends_welcome_mail_and_writes_log(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Sinh Vien',
            'email' => 'sv@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('blog'));

        Mail::assertSent(WelcomeMail::class, fn ($mail) => $mail->hasTo('sv@example.com'));

        $log = $this->logContents();
        $this->assertStringContainsString('register', $log);
        $this->assertStringContainsString('sv@example.com', $log);
        $this->assertStringNotContainsString('password123', $log);
    }

    public function test_welcome_mail_renders(): void
    {
        $user = User::factory()->create(['name' => 'Nguyen Van A']);

        $html = (new WelcomeMail($user))->render();

        $this->assertStringContainsString('Nguyen Van A', $html);
    }

    public function test_register_still_succeeds_when_mail_fails(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post('/register', [
            'name' => 'Sinh Vien',
            'email' => 'sv2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('blog'));

        $this->assertDatabaseHas('users', ['email' => 'sv2@example.com']);
    }

    public function test_register_validates_server_side(): void
    {
        $this->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '456',
        ])->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_login_writes_success_and_failure_logs(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post('/login', ['email' => $user->email, 'password' => 'sai-mat-khau'])
            ->assertSessionHasErrors('email');
        $this->assertStringContainsString('login_failed', $this->logContents());

        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect(route('blog'));

        $log = $this->logContents();
        $this->assertStringContainsString('login', $log);
        $this->assertStringContainsString((string) $user->email, $log);
        $this->assertStringNotContainsString('password123', $log);
    }

    public function test_forgot_password_sends_reset_mail_with_link(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertSessionHas('success');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $mail = $notification->toMail($user);
            $this->assertStringContainsString('/reset-password/' . $notification->token, $mail->actionUrl);

            return true;
        });

        $this->assertStringContainsString('forgot_password_requested', $this->logContents());
    }

    public function test_forgot_password_does_not_reveal_unknown_email(): void
    {
        Notification::fake();

        $this->post('/forgot-password', ['email' => 'khong-ton-tai@example.com'])
            ->assertSessionHas('success');

        Notification::assertNothingSent();
    }

    public function test_reset_password_with_valid_token_changes_password_and_logs(): void
    {
        Notification::fake();
        $user = User::factory()->create(['password' => 'old-password-1']);

        $this->post('/forgot-password', ['email' => $user->email]);

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($n) use (&$token) {
            $token = $n->token;

            return true;
        });

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-password-1',
            'password_confirmation' => 'new-password-1',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('new-password-1', $user->fresh()->password));
        $this->assertStringContainsString('password_changed', $this->logContents());
    }

    public function test_reset_password_rejects_invalid_token(): void
    {
        $user = User::factory()->create(['password' => 'old-password-1']);

        $this->post('/reset-password', [
            'token' => 'token-gia',
            'email' => $user->email,
            'password' => 'new-password-1',
            'password_confirmation' => 'new-password-1',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('old-password-1', $user->fresh()->password));
    }

    public function test_logged_in_user_can_change_password_and_it_is_logged(): void
    {
        $user = User::factory()->create(['password' => 'old-password-1']);

        $this->actingAs($user)->put('/account/password', [
            'current_password' => 'old-password-1',
            'password' => 'new-password-1',
            'password_confirmation' => 'new-password-1',
        ])->assertRedirect(route('blog'));

        $this->assertTrue(Hash::check('new-password-1', $user->fresh()->password));
        $this->assertStringContainsString('password_changed', $this->logContents());
    }

    public function test_change_password_requires_correct_current_password(): void
    {
        $user = User::factory()->create(['password' => 'old-password-1']);

        $this->actingAs($user)->put('/account/password', [
            'current_password' => 'sai',
            'password' => 'new-password-1',
            'password_confirmation' => 'new-password-1',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('old-password-1', $user->fresh()->password));
    }

    public function test_guest_cannot_open_change_password(): void
    {
        $this->get('/account/password')->assertRedirect(route('login'));
    }

    public function test_new_pages_render(): void
    {
        $this->get('/login')->assertOk()->assertSee('Quên mật khẩu?');
        $this->get('/forgot-password')->assertOk()->assertSee('QUÊN MẬT KHẨU');
        $this->get('/reset-password/abc123?email=a@b.com')->assertOk()->assertSee('abc123', false);

        $user = User::factory()->create();
        $this->actingAs($user)->get('/account/password')->assertOk()->assertSee('ĐỔI MẬT KHẨU');
    }

    public function test_blog_paginates_posts(): void
    {
        $user = User::factory()->create();
        for ($i = 1; $i <= 12; $i++) {
            \App\Models\Post::create([
                'user_id' => $user->id, 'slug' => "b-$i", 'title' => "Bài số $i",
                'category' => 'Đời sống', 'content' => 'Nội dung bài viết mẫu.', 'file_path' => null,
            ]);
        }

        $this->get('/blog')->assertOk()->assertSee('page=2', false);
        $this->get('/blog?page=2')->assertOk();
    }
}
