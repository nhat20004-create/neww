@extends('Layout.app')

@section('title', 'index')



@section('content')

    <h1>Post Index</h1>
    <ul>
        @foreach ($posts as $post)
            <li> id: {{ $post->id }}</li>
            <li> author: {{ $post->user->name }}</li>
            <li> title: {{ $post->title }}</li>
            <image src="{{ $post->file_path }}" width="500" alt="anhr nen man hinh"/>
            <li> content: {{ $post->content }}</li>
            <li> created_at: {{ $post->created_at }}</li>
            <li> updated_at: {{ $post->updated_at }}</li>
            <li> deleted_at: {{ $post->deleted_at }}</li>
            <li> <form action="{{ route('post.destroy', $post->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form> </li>

            <br>
        @endforeach
    </ul>
@endsection


