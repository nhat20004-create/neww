@extends('Layout.index')

@section('title', $post->title . ' - Góc Nhìn')

@section('content')
<div class="content-box">
    <span class="category">{{ $post->category ?? 'Khác' }}</span>
    <h2 class="post-title mt-2">{{ $post->title }}</h2>
    <p class="text-muted">
        {{ $post->created_at?->format('d/m/Y H:i') }}
        @if($post->user)
            · {{ $post->user->name }}
        @endif
    </p>

    @if($post->file_path)
        <img src="{{ asset($post->file_path) }}" alt="{{ $post->title }}" class="post-image-lg mb-4">
    @endif

    <div style="white-space: pre-line;">{{ $post->content }}</div>

    @if($post->isOwnedBy(auth()->id()))
        <hr>
        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm">Sửa bài viết</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa bài viết này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
        </form>
    @endif

    <div class="mt-3">
        <a href="{{ route('blog') }}">← Quay lại trang chủ</a>
    </div>
</div>
@endsection
