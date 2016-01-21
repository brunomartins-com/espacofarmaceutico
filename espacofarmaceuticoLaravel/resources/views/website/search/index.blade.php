@extends('template.website')

@section('title')
{{ $pages->title." - " }}
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
    <div class="col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0 col-sm-12 col-xs-12 text-page">
        <header>
            <h2 class="title pc80">{{ $pages->title }}<span class="hidden-xs hidden-sm">{{ isset($request->palavra) ? " - Tag: ".$request->palavra : "" }}</span>
            </h2>
            <div class="box-share pc20 hidden-xs">
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 hidden-sm hidden-xs">
            <h4 class="text-orange font-size-30">
                "{{ $request->inputSearch }}"
            </h4>
            <nav class="sidebar-menu search-menu margin-top-25 margin-bottom-20">
                <ul>
                    <li><a href="#" data-type="blog" class="active" title="Blog ({{ $blog->count() }})">Blog ({{ $blog->count() }})</a></li>
                    <li><a href="#" data-type="news-and-releases" title="Notícias e Releases ({{ $newsAndReleases->count() }})">Notícias e Releases ({{ $newsAndReleases->count() }})</a></li>
                </ul>
            </nav>
            <a href="#" class="btn btn-main btn-search" title="Fazer nova busca">Fazer nova busca</a>
        </aside>
        <section id="blog" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 search-results">
            @foreach($blog as $item)
            <article class="blog">
                <time>{{ $item->date->formatLocalized('%d - %B - %Y') }}</time>
                <h3><a href="{{ url('blog/'.$item->date->format('Y/m/d').'/'.$item->slug) }}" class="text-orange" title="{{ $item->title }}">{{ $item->title }}</a></h3>
                <p class="text">{{ $item->subtitle }}</p>
            </article>
            @endforeach
        </section>
        <section id="news-and-releases" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 hidden search-results">
            @foreach($newsAndReleases as $item)
            <article class="blog">
                <time>{{ $item->date->formatLocalized('%d - %B - %Y') }}</time>
                <h3><a href="{{ url('noticias-e-releases/'.$item->date->format('Y/m/d').'/'.$item->slug) }}" class="text-orange" title="{{ $item->title }}">{{ $item->title }}</a></h3>
                <p class="text">{{ $item->subtitle }}</p>
            </article>
            @endforeach
        </section>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </div>
</div>
@stop