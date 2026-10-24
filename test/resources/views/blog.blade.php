
@extends('Layout.index')

@section('title', 'Trang chủ - Góc Nhìn Đời Sống')

@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="content-box">
            <h4 class="mb-4">BÀI VIẾT MỚI NHẤT</h4>

            @if(count($posts) > 0)

                @foreach($posts as $post)
                    <div class="row mb-4 pb-4 border-bottom">
                        <div class="col-md-5">
                            <img src="{{ $post['image'] }}"
                                 alt="{{ $post['title'] }}"
                                 class="post-image">
                        </div>

                        <div class="col-md-7 mt-3 mt-md-0">
                            <span class="category">
                                {{ $post['category'] }}
                            </span>

                            <h4 class="post-title mt-2">
                                {{ $post['title'] }}
                            </h4>

                            <p class="text-muted small">
                                {{ $post['date'] }}
                            </p>

                            <p>{{ $post['description'] }}</p>

                            <a href="#bai-viet"
                               class="btn btn-outline-primary btn-sm">
                                Đọc thêm
                            </a>
                        </div>
                    </div>
                @endforeach

            @else
                <p>Hiện chưa có bài viết nào.</p>
            @endif
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
                    Công nghệ
                </li>
                <li class="list-group-item" id="hoc-tap">
                    Học tập
                </li>
                <li class="list-group-item" id="doi-song">
                    Đời sống
                </li>
            </ul>
        </div>

        <div class="content-box">
            <h5 class="post-title">BÀI VIẾT PHỔ BIẾN</h5>

            @foreach($posts as $post)
                <p class="border-bottom pb-2">
                    {{ $post['title'] }}
                </p>
            @endforeach
        </div>
    </div>
</div>

@endsection
