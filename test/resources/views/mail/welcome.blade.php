<x-mail::message>
# Xin chào {{ $user->name }}!

Cảm ơn bạn đã đăng ký tài khoản tại **Góc Nhìn**. Từ bây giờ bạn có thể đăng bài viết, kèm ảnh minh họa và quản lý các bài viết của mình.

<x-mail::button :url="route('blog')">
Truy cập Góc Nhìn
</x-mail::button>

Chúc bạn có những trải nghiệm thú vị,<br>
Đội ngũ Góc Nhìn
</x-mail::message>
