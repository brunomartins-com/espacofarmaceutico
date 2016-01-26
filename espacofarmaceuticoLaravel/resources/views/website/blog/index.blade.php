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
    <section class="col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0 col-sm-12 col-xs-12 text-page">
        <header>
            <h2 class="title pc80">{{ $pages->title }}<span class="hidden-xs hidden-sm">{{ isset($request->palavra) ? " - Tag: ". urldecode($request->palavra) : "" }}</span>
            </h2>
            <div class="box-share pc20 hidden-xs">
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 hidden-sm hidden-xs">
            <h4 class="text-orange font-size-18">
                Tags mais procuradas
            </h4>
            <div class="more-searched-tags">
                {!! $tagsMoreSearched !!}
            </div>
        </aside>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            @foreach($blog as $item)
            <article class="blog">
                @if(!empty($item->image))
                <a href="{{ url('blog/'.$item->date->format('Y/m/d').'/'.$item->slug) }}" title="{{ $item->title }}">
                    <img src="{{ asset('assets/images/_upload/blog/'.$item->image) }}" alt="{{ $item->title }}" class="img-responsive" />
                </a>
                @endif
                <time>{{ $item->date->formatLocalized('%d - %B - %Y') }}</time>
                <h3><a href="{{ url('blog/'.$item->date->format('Y/m/d').'/'.$item->slug) }}" class="text-orange" title="{{ $item->title }}">{{ $item->title }}</a></h3>
                <p class="text">{{ $item->subtitle }}</p>
            </article>
            @endforeach
            <div class="clear text-center">
                {!! str_replace('&amp;?pagina=', '&amp;pagina=', $blog->links()) !!}
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </section>
</div>
@stop