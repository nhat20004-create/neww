<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết bài viết</title>
</head>
<body>
    @if(!empty($post))
        <h2>Thông tin bài viết</h2>

        <p>ID: {{ $post->id }}</p>
        <p>Title: {{ $post->title }}</p>
        <p>Content: {{ $post->content }}</p>
    @else
        <h2>Not Found</h2>
        <p>Không tìm thấy bài viết!</p>
    @endif
</body>
</html>