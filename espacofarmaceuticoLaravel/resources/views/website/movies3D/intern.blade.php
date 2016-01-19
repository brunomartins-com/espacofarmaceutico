@extends('template.website')

@section('title')
{{ $movie3D->title." (".$pages->title.") - " }}
@stop

@section('head')
<!-- meta tags -->
<meta name="description" content="{{ $pages->description }}" />
<meta name="keywords" content="{{ $pages->keywords }}" />
<!-- Tags Facebook -->
@if(empty($movie3D->image))
<meta property="og:image" content="{{ asset('assets/images/_upload/websiteSettings/'.$websiteSettings['avatar']) }}" />
@else
<meta property="og:image" content="{{ asset('assets/images/_upload/movies3D/'.$movie3D->image) }}" />
@endif
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
        <aside class="col-lg-4 col-md-4 hidden-sm hidden-xs hidden-print">
            <h3 class="font-size-30 text-orange strong">{{ $movie3D->title }}</h3>
            <hr>
            <time>{{ $movie3D->date->formatLocalized('%d - %B - %Y') }}</time>
            <hr>
            <div class="clear"></div>
            <section class="more-blogs">
                <h5 class="text-orange font-size-18">
                    Veja também:
                </h5>
                @foreach($moreMovies3D as $item)
                <article>
                    <time>{{ $item->date->formatLocalized('%d - %B - %Y') }}</time>
                    <h4><a href="{{ url('videos-3d/'.$item->date->format('Y/m/d').'/'.$item->slug) }}" class="text-gray" title="{{ $item->title }}">{{ $item->title }}</a></h4>
                </article>
                @endforeach
            </section>
        </aside>
        <div id="col-dir" class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <article class="text">
                <div class="hidden-md hidden-lg">
                    <time>{{ $movie3D->date->formatLocalized('%d - %B - %Y') }}</time>
                    <h3 class="font-size-30 text-orange strong clear">{{ $movie3D->title }}</h3>
                    <div class="clearfix"></div>
                </div>

                <iframe src="{{ $movie3D->url }}" frameborder="0" width="100%" height="400"></iframe>
                <br>
                {!! $movie3D->description !!}
            </article>
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
$("aside").height($("#col-dir").height());
</script>
@stop