<!-- resources/views/post_create.blade.php -->

@extends('Layout.app')

@section('title', 'Create Post')

@section('content')
    <h1>Create Post</h1>
    @foreach ($errors->all() as $error)
        <p> {{ $error }} </p>
    @endforeach
    <form action="{{ route('post.store') }}" method="post"  enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Title">
        <input type="text" name="content" placeholder="Content">
        <input type="file" name="image_file" />
        <button type="submit">Create</button>
    </form>