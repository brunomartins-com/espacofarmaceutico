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
        <aside class="col-lg-4 col-md-4">
            <nav class="sidebar-menu hidden-sm hidden-xs">
                <ul>
                    @foreach($productsCategories as $category)
                        @if($category->productsCategoriesSlug == "catalogos-digitais")
                            <li><a href="{{ url('produtos/'.$category->productsCategoriesSlug) }}" @if(isset($categoryChosenSlug) and $categoryChosenSlug == $category->productsCategoriesSlug) class="active" @endif title="{{ $category->productsCategoriesName }}">{{ $category->productsCategoriesName }}</a></li>
                        @else
                            <li><a href="{{ url('produtos/categoria/'.$category->productsCategoriesSlug) }}" @if(isset($categoryChosenSlug) and $categoryChosenSlug == $category->productsCategoriesSlug) class="active" @endif title="{{ $category->productsCategoriesName }}">{{ $category->productsCategoriesName }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </nav>
            <select name="categories" id="categories" class="input-main clear margin-top-10 visible-sm visible-xs products-filter">
                <option value="{{ url('produtos') }}">Escolha...</option>
                @foreach($productsCategories as $category)
                    @if($category->productsCategoriesSlug == "catalogos-digitais")
                        <option value="{{ url('produtos/'.$category->productsCategoriesSlug) }}" @if(isset($categoryChosenSlug) and $categoryChosenSlug == $category->productsCategoriesSlug) selected @endif>{{ $category->productsCategoriesName }}</option>
                    @else
                        <option value="{{ url('produtos/categoria/'.$category->productsCategoriesSlug) }}" @if(isset($categoryChosenSlug) and $categoryChosenSlug == $category->productsCategoriesSlug) selected @endif>{{ $category->productsCategoriesName }}</option>
                    @endif
                @endforeach
            </select>
            <div class="clear visible-sm visible-xs"><hr></div>
            {!! Form::open([
                'id' => 'search',
                'method' => 'post',
                'class' => 'form-inline margin-top-20',
                'enctype' => 'multipart/form-data',
                'url' => url('produtos/busca')
                ])
            !!}
            {!! Form::text('keywords', isset($keywordsSearched) ? $keywordsSearched : null, ['id'=>'keywords', 'class'=>'clear input-main', 'placeholder'=>'Buscar por palavra-chave', 'maxlength'=>100]) !!}
            {!! Form::close() !!}
            <select name="activePrinciple" class="input-main clear products-filter margin-top-20">
                <option value="{{ url('produtos') }}">Filtrar por princípio ativo</option>
                @foreach($activePrinciples as $activePrinciple)
                    <option value="{{ url('produtos/principio-ativo/'.$activePrinciple->activePrincipleSlug) }}" @if(isset($activePrincipleChosenSlug) and $activePrincipleChosenSlug == $activePrinciple->activePrincipleSlug) selected @endif>{{ $activePrinciple->activePrinciple }}</option>
                @endforeach
            </select>
            <div class="clear visible-sm visible-xs"><hr></div>
        </aside>
        <section class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            @foreach($digitalCatalogs as $digitalCatalog)
            <article class="blog">
                <h3>{{ $digitalCatalog->title }}</h3>
                {!! $digitalCatalog->embed !!}
            </article>
            @endforeach
            <div class="clear text-center">
                {!! str_replace('&amp;?pagina=', '&amp;pagina=', $digitalCatalogs->links()) !!}
            </div>
        </section>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </div>
</div>
@stop