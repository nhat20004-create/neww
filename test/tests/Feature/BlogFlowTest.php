<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class BlogFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_create_post(): void
    {
        $this->get(route('posts.create'))->assertRedirect(route('login'));
    }

    public function test_user_can_register_login_and_logout(): void
    {
        $this->post('/register', [
            'name' => 'Sinh Vien',
            'email' => 'sv@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('blog'));

        $this->assertAuthenticated();

        $this->post('/logout')->assertRedirect(route('blog'));
        $this->assertGuest();

        $this->post('/login', [
            'email' => 'sv@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('blog'));

        $this->assertAuthenticated();
    }

    public function test_user_can_create_post_with_valid_image(): void
    {
        $user = User::factory()->create();
        $image = $this->makePngUpload('cover.png');

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Xu hướng công nghệ',
                'category' => 'Công nghệ',
                'content' => 'Nội dung bài viết đủ dài để hợp lệ.',
                'image_file' => $image,
            ])
            ->assertRedirect(route('blog'));

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Xu hướng công nghệ',
        ]);

        $post = Post::first();
        $this->assertNotEmpty($post->file_path);
        $this->assertFileExists(public_path($post->file_path));

        @unlink(public_path($post->file_path));
    }

    public function test_image_must_be_valid_format_and_size(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('note.pdf', 100, 'application/pdf');

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Sai định dạng',
                'category' => 'Công nghệ',
                'content' => 'Nội dung bài viết đủ dài để hợp lệ.',
                'image_file' => $file,
            ])
            ->assertSessionHasErrors('image_file');
    }

    public function test_user_cannot_edit_or_delete_someone_elses_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $post = Post::create([
            'user_id' => $owner->id,
            'slug' => 'bai-viet-cua-nguoi-khac',
            'title' => 'Bài của người khác',
            'category' => 'Đời sống',
            'content' => 'Nội dung bài viết của người khác.',
            'file_path' => null,
        ]);

        $this->actingAs($other)
            ->get(route('posts.edit', $post->id))
            ->assertForbidden();

        $this->actingAs($other)
            ->put(route('posts.update', $post->id), [
                'title' => 'Cố sửa',
                'category' => 'Đời sống',
                'content' => 'Nội dung bị sửa trái phép.',
            ])
            ->assertForbidden();

        $this->actingAs($other)
            ->delete(route('posts.destroy', $post->id))
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Bài của người khác']);
    }

    public function test_image_larger_than_2mb_is_rejected(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('big.jpg', 100, 100)->size(3000);

        $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Ảnh quá lớn',
                'category' => 'Công nghệ',
                'content' => 'Nội dung bài viết đủ dài để hợp lệ.',
                'image_file' => $file,
            ])
            ->assertSessionHasErrors('image_file');
    }

    public function test_owner_can_update_post_with_new_image(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'Bài gốc',
            'category' => 'Học tập',
            'content' => 'Nội dung bài viết đủ dài để hợp lệ.',
            'image_file' => $this->makePngUpload('a.png'),
        ]);
        $post = Post::first();
        $oldPath = $post->file_path;

        $this->actingAs($user)->put(route('posts.update', $post->id), [
            'title' => 'Bài đã sửa',
            'category' => 'Đời sống',
            'content' => 'Nội dung đã được chỉnh sửa xong.',
            'image_file' => $this->makePngUpload('b.png'),
        ])->assertRedirect(route('posts.show', $post->id));

        $post->refresh();
        $this->assertSame('Bài đã sửa', $post->title);
        $this->assertNotSame($oldPath, $post->file_path);
        $this->assertFileDoesNotExist(public_path($oldPath));
        $this->assertFileExists(public_path($post->file_path));

        @unlink(public_path($post->file_path));
    }

    public function test_guest_cannot_delete_or_update_post(): void
    {
        $owner = User::factory()->create();
        $post = Post::create([
            'user_id' => $owner->id, 'slug' => 'x', 'title' => 'Bài',
            'category' => 'Đời sống', 'content' => 'Nội dung bài viết.', 'file_path' => null,
        ]);

        $this->delete(route('posts.destroy', $post->id))->assertRedirect(route('login'));
        $this->put(route('posts.update', $post->id), [])->assertRedirect(route('login'));
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_non_owner_gets_403_even_with_invalid_data(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::create([
            'user_id' => $owner->id, 'slug' => 'x', 'title' => 'Bài',
            'category' => 'Đời sống', 'content' => 'Nội dung bài viết.', 'file_path' => null,
        ]);

        // Dữ liệu sai nhưng phải bị chặn bằng 403 trước khi validate
        $this->actingAs($other)
            ->put(route('posts.update', $post->id), ['title' => ''])
            ->assertForbidden();
    }

    private function makePngUpload(string $name): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;
        file_put_contents($path, $png);

        return new UploadedFile($path, $name, 'image/png', null, true);
    }
}
