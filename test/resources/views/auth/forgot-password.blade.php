@extends('Layout.index')

@section('title', 'Quên mật khẩu - Góc Nhìn')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-3">QUÊN MẬT KHẨU</h4>
            <p class="text-muted">Nhập email đã đăng ký, chúng tôi sẽ gửi liên kết để bạn đặt lại mật khẩu.</p>

            <form action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Gửi liên kết đặt lại</button>
                <a href="{{ route('login') }}" class="btn btn-light">Quay lại đăng nhập</a>
            </form>
        </div>
    </div>
</div>
@endsection
