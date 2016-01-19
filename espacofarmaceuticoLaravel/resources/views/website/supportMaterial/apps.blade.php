@extends('template.website')

@section('title')
{{ $pages->title." (Aplicativos) - " }}
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
                    <li><a href="{{ url('material-de-apoio/aplicativos') }}" class="active" title="Aplicativos">Aplicativos</a></li>
                    <li><a href="{{ url('material-de-apoio/calculadora-imc') }}" title="Calculadora IMC">Calculadora IMC</a></li>
                </ul>
            </nav>
        </aside>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            @if(count($apps) == 0)
                <article class="text"><p class="margin-top-30 text-center">Nenhum aplicativo cadastrado!</p></article>
            @endif
            @foreach($apps as $app)
            <article class="blog text">
                <div class="appsTable">
                    <img src="{{ asset('assets/images/_upload/supportMaterial/'.$app->image) }}" alt="{{ $app->title }}" class="appsImage" />
                    <h4 class="text-orange appsTitle">{{ $app->title }}</h4>
                </div>
                <div class="clear"><br></div>
                <p>{!! $app->description !!}</p>
                <ul class="appsUrl">
                    @if($app->iphoneUrl)
                    <li><a href="{{ $app->iphoneUrl }}" target="_blank" title="iPhone">iPhone</a></li>
                    @endif
                    @if($app->ipadUrl)
                    <li><a href="{{ $app->ipadUrl }}" target="_blank" title="iPad">iPad</a></li>
                    @endif
                    @if($app->androidUrl)
                    <li><a href="{{ $app->androidUrl }}" target="_blank" title="Android">Android</a></li>
                    @endif
                </ul>
            </article>
            @endforeach
            <div class="clear text-center margin-top-35">
                {!! str_replace('&amp;?pagina=', '&amp;pagina=', $apps->links()) !!}
            </div>
            <div class="box-share visible-xs">
                @include('website.share')
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </section>
</div>
@stop