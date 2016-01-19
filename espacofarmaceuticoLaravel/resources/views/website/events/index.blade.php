@extends('template.website')

@section('title')
    @if(!empty($request->type) and $request->type == "internacionais")
        {{ $pages->title." (Internacionais) - " }}
    @endif
    @if(empty($request->type) or (!empty($request->type) and $request->type == "nacionais"))
        {{ $pages->title." (Nacionais) - " }}
    @endif
@stop

@section('head')
<!-- meta tags -->
<meta name="description" content="{{ $pages->description }}" />
<meta name="keywords" content="{{ $pages->keywords }}" />
<!-- Tags Facebook -->
<meta property="og:image" content="{{ asset('assets/images/_upload/websiteSettings/'.$websiteSettings['avatar']) }}" />
<meta property="og:description" content="{{ $pages->description }}" />
@stop

@section('content')
<div class="container">
    <section class="col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0 col-sm-12 col-xs-12 text-page">
        <header>
            <h2 class="title pc80">{{ $pages->title }}</h2>
            <div class="box-share pc20 hidden-xs">
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="{{ url('eventos/nacionais') }}" @if(empty($request->type) or (!empty($request->type) and $request->type == "nacionais")) class="active" @endif title="Nacionais">Nacionais</a></li>
                    <li><a href="{{ url('eventos/internacionais') }}" @if(!empty($request->type) and $request->type == "internacionais") class="active" @endif title="Internacionais">Internacionais</a></li>
                </ul>
            </nav>
        </aside>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            @if(count($events) == 0)
                <article class="text"><p class="margin-top-30 text-center">Nenhum evento @if(isset($request->type) and $request->type == 'internacionais'){{ 'internacional' }} @else {{ 'nacional' }} @endif cadastrado!</p></article>
            @endif
            @foreach($events as $event)
            <article class="blog">
                <h4 class="text-orange">{{ $event->title }}</h4>
                {!! $event->details !!}
            </article>
            @endforeach
            <div class="clear text-center margin-top-35">
                {!! str_replace('&amp;?pagina=', '&amp;pagina=', $events->links()) !!}
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </section>
</div>
@stop