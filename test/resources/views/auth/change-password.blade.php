@extends('Layout.index')

@section('title', 'Đổi mật khẩu - Góc Nhìn')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐỔI MẬT KHẨU</h4>

            <form action="{{ route('password.change.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                <a href="{{ route('blog') }}" class="btn btn-light">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection
