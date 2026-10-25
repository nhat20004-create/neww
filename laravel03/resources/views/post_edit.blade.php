<!-- resources/views/post_edit.blade.php -->

@extends('Layout.app')

@section('title', 'Edit Post')

@section('content')
    <h1>Edit Post</h1>
    <form action="{{ route('post.update', $post->id) }}" method="post">
        @csrf
        @method('PUT')
        <input type="text" name="title" placeholder="Title" value="{{ $post->title }}">
        <input type="text" name="content" placeholder="Content" value="{{ $post->content }}">
        <button type="submit">Update</button>
    </form>
@endsection