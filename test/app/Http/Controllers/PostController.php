<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $category = request('category');

        $posts = Post::with('user')
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('blog', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);

        return view('post_show', ['post' => $post]);
    }

    public function create()
    {
        return view('post_create');
    }

    public function store(StorePostRequest $request)
    {
        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->category = $request->category;
        $post->content = $request->content;
        $post->slug = $this->makeSlug($request->title);
        $post->file_path = $this->storeImage($request->file('image_file'));
        $post->save();

        return redirect()->route('blog')->with('success', 'Đã tạo bài viết.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->ensureOwner($post);

        return view('post_edit', ['post' => $post]);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $this->ensureOwner($post);

        $post->title = $request->title;
        $post->category = $request->category;
        $post->content = $request->content;
        $post->slug = $this->makeSlug($request->title);

        if ($request->hasFile('image_file')) {
            $this->deleteImage($post->file_path);
            $post->file_path = $this->storeImage($request->file('image_file'));
        }

        $post->save();

        return redirect()->route('posts.show', $post->id)->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $this->ensureOwner($post);

        $this->deleteImage($post->file_path);
        $post->delete();

        return redirect()->route('blog')->with('success', 'Đã xóa bài viết.');
    }

    private function ensureOwner(Post $post): void
    {
        if (!$post->isOwnedBy(Auth::id())) {
            abort(403, 'Bạn không được thao tác với bài viết của người khác.');
        }
    }

    private function makeSlug(string $title): string
    {
        $slug = Str::slug($title);

        return $slug !== '' ? $slug : 'bai-viet-' . time();
    }

    private function storeImage(UploadedFile $file): string
    {
        // hashName(): tên ngẫu nhiên + đuôi suy ra từ nội dung file, không tin đuôi do client gửi
        $fileName = $file->hashName();
        $file->move(public_path('upload'), $fileName);

        return 'upload/' . $fileName;
    }

    private function deleteImage(?string $path): void
    {
        if (!$path) {
            return;
        }

        $fullPath = public_path($path);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
