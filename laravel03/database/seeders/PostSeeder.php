<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có sẵn User trước khi tạo Post
        if (User::count() === 0) {
            $this->call(UserSeeder::class);
        }

        $userIds = User::pluck('id')->toArray();

        $posts = [
            [
                'title' => 'Cài đặt môi trường Laravel 12',
                'content' => 'Hướng dẫn chi tiết cách cài đặt PHP 8.4, Composer và khởi tạo dự án Laravel 12 trên macOS và Windows.',
            ],
            [
                'title' => 'Tìm hiểu kiến trúc MVC trong Laravel',
                'content' => 'Mô hình MVC (Model - View - Controller) giúp phân tách rõ ràng giữa giao diện, logic xử lý và dữ liệu ứng dụng.',
            ],
            [
                'title' => 'Quản lý cơ sở dữ liệu với Migration',
                'content' => 'Migration hoạt động như một hệ thống kiểm soát phiên bản (Git) dành cho cơ sở dữ liệu của bạn.',
            ],
            [
                'title' => 'Tạo và chạy Seeder trong Laravel',
                'content' => 'Seeder cho phép tạo dữ liệu mẫu nhanh chóng để kiểm thử tính năng và chuẩn bị bài thuyết trình demo.',
            ],
            [
                'title' => 'Sử dụng Eloquent ORM và Model',
                'content' => 'Eloquent ORM cung cấp cú pháp hướng đối tượng trực quan để tương tác với cơ sở dữ liệu thay vì viết raw SQL.',
            ],
            [
                'title' => 'Xây dựng Routing và Controller',
                'content' => 'Cách định tuyến URL đến các phương thức trong Controller, truyền tham số và quản lý Route Groups.',
            ],
            [
                'title' => 'Blade Template Engine và Layout',
                'content' => 'Kế thừa layout, include sub-view và truyền dữ liệu từ Controller sang View với cú pháp Blade ngắn gọn.',
            ],
            [
                'title' => 'Validation dữ liệu người dùng',
                'content' => 'Cách kiểm tra tính hợp lệ của dữ liệu đầu vào với Validate rules và xử lý hiển thị thông báo lỗi thân thiện.',
            ],
            [
                'title' => 'Thiết lập quan hệ One-to-Many',
                'content' => 'Khai báo quan hệ một - nhiều giữa bảng Users và bảng Posts với hasMany và belongsTo trong Eloquent.',
            ],
            [
                'title' => 'Tổng kết bài tập buổi 2 PHP Laravel',
                'content' => 'Hoàn thành bài tập tạo migration, quan hệ bảng và seeder dữ liệu mẫu cho ứng dụng demo quản lý bài viết.',
            ],
        ];

        foreach ($posts as $index => $item) {
            $userId = $userIds[$index % count($userIds)];
            $title = Str::limit($item['title'], 50, '');
            $slug = Str::limit(Str::slug($item['title']), 50, '');

            Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $userId,
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $item['content'],
                    'created_at' => now()->subDays(10 - $index),
                    'updated_at' => now()->subDays(10 - $index),
                ]
            );
        }
    }
}