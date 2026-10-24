<header class="bg-white text-center py-4">
    <a href="{{ url('/blog') }}" class="brand text-decoration-none">
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
                    <a class="nav-link" href="{{ url('/blog') }}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#cong-nghe">Công nghệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hoc-tap">Học tập</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#doi-song">Đời sống</a>
                </li>
            </ul>
        </div>
    </div>
</nav>