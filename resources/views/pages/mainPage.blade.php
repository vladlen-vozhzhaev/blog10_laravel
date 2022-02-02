@extends('layouts.main')
@section('h1', 'Главная')
@section('content')
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
        @foreach($articles as $article)
            <!-- Post preview-->
            <div class="post-preview">
                <a href="/blog/{{$article->id}}">
                    <h2 class="post-title">{{$article->title}}</h2>
                    <h3 class="post-subtitle">{{$article->content}}</h3>
                </a>
                <p class="post-meta">
                    Posted by
                    <a href="#!">{{$article->author}}</a>
                    on {{$article->created_at}}
                </p>
            </div>
            <!-- Divider-->
            <hr class="my-4" />
        @endforeach


            <!-- Pager-->
            <div class="d-flex justify-content-end mb-4"><a class="btn btn-primary text-uppercase" href="#!">Older Posts →</a></div>
        </div>
    </div>
@endsection
