<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('comments')->insert([
                'post_id' => $i,
                'content' => 'Binh luan so ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}