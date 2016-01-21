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
            <h2 class="title pc60">{{ $pages->title }}</h2>
            <div class="box-share pc40 hidden-xs">
                @include('website.share')
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <h3 class="text-orange font-size-24">{{ $pages->description }}</h3>
        </aside>
        <section class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <article class="text">
                {!! $mainText->text !!}
            </article>
            <div class="clear"><hr></div>
            <article class="text padding-top-0 padding-bottom-10 clear">
                <h4 class="font-size-24 text-orange">Cadastre seu currículo</h4>
                <div class="pull-left pc50 margin-right-20">
                    {{ $complementText->text }}
                </div>
                <a href="{{ $link->text }}" target="_blank" class="btn btn-main pull-left">Cadastrar Currículo</a>
            </article>
            <div class="clear"><hr></div>
            @if(count($vacancies) > 0)
            <div class="clear">
                <h4 class="font-size-24 text-orange">Quadro de vagas</h4>
                @foreach($vacancies as $key => $vacancy)
                <article class="text vacancy @if(($key % 2) != 0){{ 'bg-white' }}@endif">
                    <h5 class="strong">{{ $vacancy->title }}</h5>
                    <p>{!! nl2br($vacancy->details) !!}</p>
                </article>
                @endforeach
            </div>
            @endif
        </section>
        <div class="box-share visible-xs">
            @include('website.share')
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </div>
</div>
@stop