@extends('layouts.main')
@section('h1', "$article->title")
@section('content')
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    {!!  $article->content !!}
                    <div class="my-3">
                        Автор: {{$article->author}} | Дата публикации: {{$article->created_at}}
                    </div>
                </div>
            </div>
        </div>
    </article>
@endsection
