<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Công nghệ', 'Học tập', 'Đời sống'];

        for ($i = 1; $i <= 10; $i++) {
            DB::table('posts')->insert([
                'user_id' => $i,
                'slug' => 'bai-viet-' . $i,
                'title' => 'Bài viết số ' . $i,
                'category' => $categories[($i - 1) % 3],
                'content' => 'Nội dung bài viết số ' . $i . '. Đây là dữ liệu mẫu cho trang Góc Nhìn.',
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
