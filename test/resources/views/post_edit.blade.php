@extends('Layout.index')

@section('title', 'Sửa bài viết - Góc Nhìn')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-4">SỬA BÀI VIẾT</h4>

    <form action="{{ route('posts.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group">
            <label for="category">Chủ đề</label>
            <select name="category" id="category" class="form-control" required>
                @foreach(\App\Models\Post::CATEGORIES as $item)
                    <option value="{{ $item }}" @selected(old('category', $post->category) === $item)>{{ $item }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" id="content" rows="8" class="form-control" required>{{ old('content', $post->content) }}</textarea>
        </div>

        @if($post->file_path)
            <div class="form-group">
                <label>Ảnh hiện tại</label>
                <div>
                    <img src="{{ asset($post->file_path) }}" alt="{{ $post->title }}" style="max-width: 320px;">
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="image_file">Đổi ảnh (không bắt buộc — jpeg, jpg, png, gif, webp, tối đa 2MB)</label>
            <input type="file" name="image_file" id="image_file" class="form-control-file" accept="image/jpeg,image/png,image/gif,image/webp">
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-light">Hủy</a>
    </form>
</div>
@endsection
