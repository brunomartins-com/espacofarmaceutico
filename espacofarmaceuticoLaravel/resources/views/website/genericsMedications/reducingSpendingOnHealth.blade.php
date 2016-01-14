@extends('template.website')

@section('title')
{{ "Redução dos gastos com a saúde (".$pages->title.") - " }}
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
                    <li><a href="{{ url('medicamentos-genericos/perguntas-frequentes') }}" title="Perguntas Frequentes">Perguntas Frequentes</a></li>
                    <li><a href="{{ url('medicamentos-genericos/conceito') }}" title="Conceito">Conceito</a></li>
                    <li><a href="{{ url('medicamentos-genericos/reducao-dos-gastos-com-a-saude') }}" class="active" title="Redução dos gastos com a saúde">Redução dos gastos com a saúde</a></li>
                    <li><a href="{{ url('medicamentos-genericos/legislacao') }}" title="Legislação">Legislação</a></li>
                    <li><a href="{{ url('medicamentos-genericos/confiabilidade-e-qualidade') }}" title="Confiabilidade e Qualidade">Confiabilidade e Qualidade</a></li>
                </ul>
            </nav>
        </aside>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            @foreach($texts as $text)
            <article class="text">
                <h4>{{ $text->title }}</h4>
                {!! $text->text !!}
            </article>
            @endforeach
        </div>
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