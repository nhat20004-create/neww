<header class="bg-white text-center py-4">
    <a href="{{ route('blog') }}" class="brand text-decoration-none">
        GÓC NHÌN
    </a>

    <p class="text-muted mb-0 mt-2">
        Tin tức, kiến thức và câu chuyện mỗi ngày
    </p>
</header>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler bg-light"
                type="button"
                data-toggle="collapse"
                data-target="#mainMenu">
            Menu
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog', ['category' => 'Công nghệ']) }}">Công nghệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog', ['category' => 'Học tập']) }}">Học tập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog', ['category' => 'Đời sống']) }}">Đời sống</a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('posts.create') }}">Viết bài</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('password.change') }}">Xin chào, {{ auth()->user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="border:0;">Đăng xuất</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
