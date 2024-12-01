@extends('layout')

@section('title', 'Trang chủ')

@section('content')
    <a href="{{ route('logout') }}" class="btn btn-outline-secondary">Đăng xuất</a>
    <div>
        @foreach ($posts as $post)
            <div>
                <a href="{{ route('detail', $post->id) }}">
                    <h3>{{ $post->title }}</h3>
                </a>
                <p>{{ $post->description }}</p>
                <hr>
            </div>
        @endforeach
    </div>
@endsection
