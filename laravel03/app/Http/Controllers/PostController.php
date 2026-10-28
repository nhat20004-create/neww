<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Core\Uuid;

class PostController extends Controller
{
    // lấy tất cả 
    public function index() {
        $posts = Post::with('user')->get();
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

    public function store(StorePost $request) {
        $post = new Post();

        $file = $request->file('image_file');
        $file_name = date('Ymd') . random_int(10000, 10000000) . '.' . $file->getClientOriginalExtension();
        $file->move('upload', $file_name); // /upload/{{ filename }}
        $post->user_id = 2;
        $post->slug = Str::slug($request->title);
        $post->title = $request->title ?? 'default';
        $post->content = $request->content ?? 'default';
        $post->file_path = 'upload/' . $file_name;
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
        $post = Post::withTrashed()->find($id);
        if ($post->trashed()) {
            $post->forceDelete();
        } else {
            $post->delete();
        }
        return redirect()->route('post.index');
    }
}