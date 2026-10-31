@extends('Layout.index')

@section('title', 'Trang chủ - Góc Nhìn Đời Sống')

@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="content-box">
            <h4 class="mb-4">
                @if(!empty($category))
                    CHỦ ĐỀ: {{ $category }}
                @else
                    BÀI VIẾT MỚI NHẤT
                @endif
            </h4>

            @forelse($posts as $post)
                @php
                    $images = [
                        'https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=800',
                        'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800',
                        'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800',
                        'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800',
                        'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=800',
                        'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800',
                        'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
                        'https://images.unsplash.com/photo-1553877522-43269d4ea984?w=800',
                        'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800',
                        'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800'
                    ];

                    $image = $post->file_path
                        ? asset($post->file_path)
                        : $images[($post->id - 1) % count($images)];
                @endphp

                <div class="row mb-4 pb-4 border-bottom">
                    <div class="col-md-5">
                        <img src="{{ $image }}" alt="{{ $post->title }}" class="post-image">
                    </div>

                    <div class="col-md-7 mt-3 mt-md-0">
                        <span class="category">
                            {{ $post->category ?? 'Khác' }}
                        </span>

                        <h4 class="post-title mt-2">
                            {{ $post->title }}
                        </h4>

                        <p class="text-muted small">
                            {{ $post->created_at?->format('d/m/Y') }}
                            @if($post->user)
                                · {{ $post->user->name }}
                            @endif
                        </p>

                        <p>{{ Str::limit(strip_tags($post->content), 140) }}</p>

                        <a href="{{ route('posts.show', $post->id) }}"
                           class="btn btn-outline-primary btn-sm">
                            Đọc thêm
                        </a>

                        @if($post->isOwnedBy(auth()->id()))
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary btn-sm">Sửa</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="post" class="d-inline" onsubmit="return confirm('Xóa bài viết này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Xóa</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p>Hiện chưa có bài viết nào.</p>
                @auth
                    <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">Viết bài đầu tiên</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Đăng nhập để viết bài</a>
                @endauth
            @endforelse

            <div class="d-flex justify-content-center">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        </div>

        <div class="content-box" id="bai-viet">
            <h4>GÓC CHIA SẺ</h4>
            <p>
                Góc Nhìn là nơi chia sẻ kiến thức về công nghệ,
                học tập và cuộc sống.
            </p>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="content-box">
            <h5 class="post-title">GIỚI THIỆU</h5>
            <p>
                Chào mừng bạn đến với Góc Nhìn,
                nơi chia sẻ những điều thú vị mỗi ngày.
            </p>
        </div>

        <div class="content-box">
            <h5 class="post-title">CHỦ ĐỀ</h5>

            <ul class="list-group list-group-flush">
                <li class="list-group-item" id="cong-nghe">
                    <a href="{{ route('blog', ['category' => 'Công nghệ']) }}">Công nghệ</a>
                </li>
                <li class="list-group-item" id="hoc-tap">
                    <a href="{{ route('blog', ['category' => 'Học tập']) }}">Học tập</a>
                </li>
                <li class="list-group-item" id="doi-song">
                    <a href="{{ route('blog', ['category' => 'Đời sống']) }}">Đời sống</a>
                </li>
            </ul>
        </div>

        <div class="content-box">
            <h5 class="post-title">BÀI VIẾT MỚI</h5>

            @forelse($posts->take(5) as $post)
                <p class="border-bottom pb-2">
                    <a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a>
                </p>
            @empty
                <p class="text-muted mb-0">Chưa có bài viết.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
