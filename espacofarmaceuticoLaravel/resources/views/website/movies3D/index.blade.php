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
            <h2 class="title pc80">{{ $pages->title }}</h2>
            <div class="box-share pc20 hidden-xs">
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 hidden-sm hidden-xs">
            <h4 class="text-orange font-size-18">
                Vídeos mais vistos
            </h4>
            <div class="more-searched-tags">
                @foreach($moviesMoreWatched as $movieMoreWatched)
                <a href="{{ url('videos-3d/'.$movieMoreWatched->date->format('Y/m/d').'/'.$movieMoreWatched->slug) }}" title="{{ $movieMoreWatched->title }}">{{ $movieMoreWatched->title }}</a>
                <br>
                @endforeach
            </div>
        </aside>
        <section class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            @foreach($movies3D as $movie3D)
            <article class="blog">
                @if(!empty($movie3D->image))
                <a href="{{ url('videos-3d/'.$movie3D->date->format('Y/m/d').'/'.$movie3D->slug) }}" title="{{ $movie3D->title }}">
                    <img src="{{ asset('assets/images/_upload/movies3D/'.$movie3D->image) }}" alt="{{ $movie3D->title }}" class="img-responsive" />
                </a>
                @endif
                <time>{{ $movie3D->date->formatLocalized('%d - %B - %Y') }}</time>
                <h3><a href="{{ url('videos-3d/'.$movie3D->date->format('Y/m/d').'/'.$movie3D->slug) }}" class="text-orange" title="{{ $movie3D->title }}">{{ $movie3D->title }}</a></h3>
            </article>
            @endforeach
            <div class="clear text-center">
                {!! str_replace('&amp;?pagina=', '&amp;pagina=', $movies3D->links()) !!}
            </div>
        </section>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </div>
</div>
@stop