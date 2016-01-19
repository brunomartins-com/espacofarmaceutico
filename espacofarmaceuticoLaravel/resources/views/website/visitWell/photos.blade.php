@extends('template.website')

@section('title')
{{ $pages->title." (Fotos) - " }}
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
                    <li><a href="{{ url('visite-bem') }}" title="Sobre o Visite Bem">Sobre o Visite Bem</a></li>
                    <li><a href="{{ url('visite-bem/fotos') }}" class="active" title="Fotos">Fotos</a></li>
                    <li><a href="{{ url('visite-bem/agende-sua-visita') }}" title="Agende sua visita">Agende sua visita</a></li>
                </ul>
            </nav>
            @if(!isset($request) and count($moreGalleries) > 0)
            <section class="more-blogs hidden-sm hidden-xs">
                <h5 class="text-orange font-size-18">
                    Outras galerias:
                </h5>

                @foreach($moreGalleries as $moreGallery)
                    <article>
                        <time>{{ $moreGallery->date->formatLocalized('%d - %B - %Y') }}</time>
                        <h4><a href="{{ url('visite-bem/fotos/'.$moreGallery->date->format('Y/m/d').'/'.$moreGallery->slug) }}" class="text-gray" title="{{ $moreGallery->title }}">{{ $moreGallery->title }}</a></h4>
                    </article>
                @endforeach
            </section>
            @endif
        </aside>
        <div id="col-dir" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text">
            <hr class="remove-margin-t hidden-lg hidden-md">
            <p class="font-size-16 text-orange strong">Buscar foto</p>
            {!! Form::open([
                'id' => 'filter',
                'method' => 'post',
                'class' => 'form-inline row margin-top-20',
                'enctype' => 'multipart/form-data',
                'url' => url('visite-bem/fotos')
                ])
            !!}
            <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                {!! Form::select('year', $years, isset($request->year) ? $request->year : '', ['class'=>'input-main clear', 'data-type'=>'0']) !!}
            </div>
            <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                {!! Form::select('month', $months, isset($request->month) ? $request->month : '', ['class'=>'input-main clear', 'data-type'=>'0']) !!}
            </div>
            <div class="form-group col-lg-5 col-md-5 col-sm-10 col-xs-12">
                <select name="slug" class="input-main clear" data-type="1">
                    <option value="">Turma</option>
                    @foreach($allGalleries as $allGallery)
                    <option value="{{ $allGallery->slug }}" @if(isset($request) and $allGallery->slug == $request->slug) selected @endif>{{ $allGallery->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                <button type="submit" class="btn btn-main">Ok</button>
            </div>
            {!! Form::close() !!}
            @if (isset($errors) and count($errors) > 0)
                <div class="col-lg-12 col-xs-12 alert alert-danger margin-top-15">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <hr>
            @if(isset($request))
                @if(count($gallery) == 0)
                    <article class="text clear text-center"><p>Nenhuma galeria encontrada</p></article>
                @endif
                @foreach($gallery as $photo)
                    <article>
                        <time>{{ $photo->date->formatLocalized('%d - %B - %Y') }}</time>
                        <h3 class="font-size-24 clear text-orange margin-bottom-20 strong">{{ $photo->title }}</h3>
                        <img src="{{ asset('assets/images/_upload/visitWell/'.$photo->image) }}" class="img-responsive" alt="{{ $photo->title }}" />
                    </article>
                    <hr>
                @endforeach
            @else
                <article>
                    <time>{{ $gallery->date->formatLocalized('%d - %B - %Y') }}</time>
                    <h3 class="font-size-24 clear text-orange margin-bottom-20 strong">{{ $gallery->title }}</h3>
                    <img src="{{ asset('assets/images/_upload/visitWell/'.$gallery->image) }}" class="img-responsive" alt="{{ $gallery->title }}" />
                </article>
            @endif
            <div class="box-share right">
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

@section('javascript')
<script>
@if(!isset($request))
    if($('body').width() >= 992) {
        $("aside").height($("#col-dir").height());
    }
@endif
$(document).ready(function(){
    $('form#filter select').change(function(){
        if($(this).data('type') == 0){
            $('form#filter select[data-type=1]').val('');
        }else{
            $('form#filter select[data-type=0]').val('');
        }
    });
});
</script>
@stop