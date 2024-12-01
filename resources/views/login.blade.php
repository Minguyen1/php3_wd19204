@extends('layout')

@section('title', 'Đăng ký')

@section('content')
    <div class="container">
        <h2>Đăng nhập</h2>
        @if ($errors->any())
            <div class="mb-3">
                @foreach ($errors->all() as $error)
                    <div>
                        <span class="text-danger">{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        @section('message')
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endsection
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="" class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Mật khẩu</label>
                <input type="password" name="password" id="" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Đăng nhập</button>
            <a href="{{ route('register') }}" class="btn btn-primary">Đăng ký</a>
        </form>
    </div>
@endsection