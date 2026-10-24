<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    // Lấy tất cả bài viết
    public function index()
    {
        $posts = DB::table('posts')->get();

        return view('post_index', ['posts' => $posts]);
    }

    // Lấy bài viết theo ID trên URL
    public function show($id)
    {
        $post = DB::table('posts')
            ->where('id', $id)
            ->first();

        return view('post_show', ['post' => $post]);
    }
}