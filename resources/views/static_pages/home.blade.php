@extends('layouts.default')

@section('title', '首页')

@section('content')
    @if (Auth::check())
        <div class="row">
            <div class="col-md-8">
                <section class="status_form">
                    @include('common._status_form')
                </section>
                <h3>微博列表</h3>
                @include('common._feed')
            </div>
            <aside class="col-md-4">
                <section class="user_info">
                    @include('common._user_info', ['user' => Auth::user()])
                </section>
                <section class="stats">
                    @include('common._stats', ['user' => Auth::user()])
                </section>
            </aside>
        </div>
    @else
        <div class="jumbotron">
            <h1>Hello Laravel</h1>
            <p class="lead">
                你现在所看到的是 <a href="#">Laravel Alpha</a> 的项目主页。
            </p>
            <p>
                一切，将从这里开始。
            </p>
            <p>
                <a class="btn btn-lg btn-success" href="{{ route('signup') }}" role="button">现在注册</a>
            </p>
        </div>
    @endif
@stop
