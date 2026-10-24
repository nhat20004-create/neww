<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('posts')->insert([
                'user_id' => $i,
                'slug' => 'bai-viet-' . $i,
                'title' => 'Bai viet so ' . $i,
                'content' => 'Noi dung bai viet so ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}