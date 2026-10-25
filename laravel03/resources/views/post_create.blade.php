<!-- resources/views/post_create.blade.php -->

@extends('Layout.app')

@section('title', 'Create Post')

@section('content')
    <h1>Create Post</h1>
    <form action="{{ route('post.store') }}" method="post">
        @csrf
        <input type="text" name="title" placeholder="Title">
        <input type="text" name="content" placeholder="Content">
        <button type="submit">Create</button>
    </form>