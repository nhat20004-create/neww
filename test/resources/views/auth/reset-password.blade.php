@extends('Layout.index')

@section('title', 'Đặt lại mật khẩu - Góc Nhìn')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="content-box">
            <h4 class="post-title mb-4">ĐẶT LẠI MẬT KHẨU</h4>

            <form action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $email) }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
            </form>
        </div>
    </div>
</div>
@endsection
