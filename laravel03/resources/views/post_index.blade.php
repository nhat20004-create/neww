@extends('Layout.app')

@section('title', 'index')



@section('content')

    <h1>Post Index</h1>
    <ul>
        @foreach ($posts as $post)
            <li> id: {{ $post->id }}</li>
            <li> title: {{ $post->title }}</li>
            <li> content: {{ $post->content }}</li>
            <li> created_at: {{ $post->created_at }}</li>
            <li> updated_at: {{ $post->updated_at }}</li>
            <li> <form action="{{ route('post.destroy', $post->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form> </li>
        @endforeach
    </ul>
@endsection


