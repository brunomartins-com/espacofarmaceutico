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
            <h2 class="title pc60">{{ $pages->title }}</h2>
            <div class="box-share pc40 hidden-xs">
                @include('website.share')
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <h3 class="text-orange font-size-24">{{ $pages->description }}</h3>
            <div class="calls margin-top-20 hidden-xs hidden-sm">
                <a href="#" id="teuto-360" title="Teuto 360º">
                    <img src="{{ asset('assets/images/teuto-360.jpg') }}" class="img-responsive" alt="Teuto 360º" />
                </a>
            </div>
            <div class="hidden-xs hidden-sm">
                <iframe width="100%" height="230" src="{!! $movie->text !!}" frameborder="0" allowfullscreen></iframe>
            </div>
        </aside>
        <article class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text">
            {!! $text->text !!}
            <div class="visible-sm visible-xs movie">
                <iframe width="100%" height="300" src="{{ $movie->text }}" frameborder="0" allowfullscreen></iframe>
            </div>
        </article>
        <div class="box-share visible-xs">
            @include('website.share')
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </section>
</div>
@stop