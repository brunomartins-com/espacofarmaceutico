@extends('template.website')

@section('title')
{{ $pages->title." (Sobre) - " }}
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
            <nav class="sidebar-menu">
                <ul>
                    <li><a href="{{ url('visite-bem') }}" class="active" title="Sobre o Visite Bem">Sobre o Visite Bem</a></li>
                    <li><a href="{{ url('visite-bem/fotos') }}" title="Fotos">Fotos</a></li>
                    <li><a href="{{ url('visite-bem/agende-sua-visita') }}" title="Agende sua visita">Agende sua visita</a></li>
                </ul>
            </nav>
        </aside>
        <article class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text">
            {!! $text->text !!}
        </article>
        <div class="box-share right">
            @include('website.share')
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </section>
</div>
@stop