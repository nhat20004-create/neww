@extends('Layout.index')

@section('title', 'Viết bài - Góc Nhìn')

@section('content')
<div class="content-box">
    <h4 class="post-title mb-4">TẠO BÀI VIẾT MỚI</h4>

    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-group">
            <label for="category">Chủ đề</label>
            <select name="category" id="category" class="form-control" required>
                <option value="">-- Chọn chủ đề --</option>
                @foreach(\App\Models\Post::CATEGORIES as $item)
                    <option value="{{ $item }}" @selected(old('category') === $item)>{{ $item }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" id="content" rows="8" class="form-control" required>{{ old('content') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image_file">Ảnh bài viết (jpeg, jpg, png, gif, webp — tối đa 2MB)</label>
            <input type="file" name="image_file" id="image_file" class="form-control-file" accept="image/jpeg,image/png,image/gif,image/webp" required>
        </div>

        <button type="submit" class="btn btn-primary">Đăng bài</button>
        <a href="{{ route('blog') }}" class="btn btn-light">Hủy</a>
    </form>
</div>
@endsection
