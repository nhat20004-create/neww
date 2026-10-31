@extends('Layout.index')

@section('title', 'Đăng ký - Góc Nhìn')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐĂNG KÝ TÀI KHOẢN</h4>

            <form action="{{ route('register') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="name">Họ tên</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Đăng ký</button>
            </form>

            <p class="mt-3 mb-0">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
            </p>
        </div>
    </div>
</div>
@endsection
