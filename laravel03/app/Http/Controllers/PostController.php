<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // lấy tất cả 
    public function index() {
        $posts = Post::all(); // DB::table('posts')->where('title', 'like', '%Laravel%')->get();
        return view('post_index', ['posts' => $posts]);
    }

    // lấy bải ghi theo id
    public function show($id) {
        $post = Post::select('id', 'title', 'content', 'created_at', 'updated_at')
        ->where('id', $id)->first();
        return view('post_show', ['post' => $post]);
    }

    public function create() {
        return view('post_create');
    }

    public function store(Request $request) {
        $post = new Post();
        $post->user_id = 2;
        $post->slug = Str::slug($request->title);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();
        return redirect()->route('post.index');
    }

    public function edit($id) {

        $post = Post::select('id', 'title', 'content', 'created_at', 'updated_at')
        ->where('id', $id)->first();
        return view('post_edit', ['post' => $post]);
    }

    public function update(Request $request, $id) {
        $post = Post::find($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();
        return redirect()->route('post.index');
    }

    public function destroy($id) {
        $post = Post::find($id);
        $post->delete();
        return redirect()->route('post.index');
    }
}