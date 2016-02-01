@extends('template.website')

@section('head')
<link rel="stylesheet" href="{!! asset('assets/css/owl.carousel.css') !!}" />
<link rel="stylesheet" href="{!! asset('assets/css/wow.slider.css') !!}" />
<!-- meta tags -->
<meta name="description" content="{{ $pages->description }}" />
<meta name="keywords" content="{{ $pages->keywords }}" />
<!-- Tags Facebook -->
<meta property="og:image" content="{{ asset('assets/images/_upload/websiteSettings/'.$websiteSettings['avatar']) }}" />
<meta property="og:description" content="{{ $pages->description }}" />
@if (isset($errors) and count($errors) > 0)
<script>
    @foreach ($errors->all() as $error)
    var complementError = '{{ $error }}\n';
    @endforeach
    alert('Ops! Não foi possível autenticar o acesso com os dados fornecidos\n'+complementError);
</script>
@endif
@if (Session::has('success'))
<script>
    alert('{!! Session::get('success') !!}');
</script>
@endif
@stop

@section('content')
<section id="banners" class="owl-carousel owl-theme">
    @foreach($banners as $banner)
    <article class="item">
        @if($banner->url != "")
        <a href="{{ $banner->url }}" target="{{ $banner->target }}">
        @endif
            <img src="{{ url('assets/images/_upload/banners/'.$banner->image) }}" alt="{{ $banner->title }}" class="img-responsive" />
        @if($banner->url != "")
        </a>
        @endif
        <h2>{{ $banner->title }}</h2>
        <h3>{{ $banner->subtitle }}</h3>
        @if($banner->url != "")
        <a href="{{ $banner->url }}" target="{{ $banner->target }}" class="btn btn-main" title="Saiba Mais">Saiba Mais</a>
        @endif
    </article>
    @endforeach
</section>
<div class="news-releases-blog">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                <header>
                    <h2 class="title-home">Notícias e Releases</h2>
                    <div class="customNavigation">
                        <a href="{{ url('noticias-e-releases') }}" class="all" title="Veja Mais">Veja Mais</a>
                        <a id="news-and-releases-prev" class="prev" title="Anterior">Anterior</a>
                        <a id="news-and-releases-next" class="next" title="Próximo">Próximo</a>
                    </div>
                </header>
                <section id="news-and-releases" class="owl-carousel owl-theme">
                    @foreach($newsAndReleases as $newsAndRelease)
                    <article class="item">
                        <time>{{ $newsAndRelease->date->format('d/m/Y') }}</time>
                        <h2><a href="{{ url('noticias-e-releases/'.$newsAndRelease->date->format('Y/m/d').'/'.$newsAndRelease->slug) }}" title="{{ $newsAndRelease->title }}">{{ $newsAndRelease->title }}</h2>
                        <h3>
                            <a href="{{ url('noticias-e-releases/'.$newsAndRelease->date->format('Y/m/d').'/'.$newsAndRelease->slug) }}">{{ $newsAndRelease->subtitle }}</a>
                        </h3>
                    </article>
                    @endforeach
                </section>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <header>
                    <h2 class="title-home">Blog</h2>
                    <div class="customNavigation">
                        <a href="{{ url('blog') }}" class="all" title="Veja Mais">Veja Mais</a>
                        <a id="blog-prev" class="prev" title="Anterior">Anterior</a>
                        <a id="blog-next" class="next" title="Próximo">Próximo</a>
                    </div>
                </header>
                <section id="blog" class="owl-carousel owl-theme">
                    @foreach($blog as $item)
                    <article class="item">
                        <time>{{ $item->date->format('d/m/Y') }}</time>
                        <h2><a href="{{ url('blog/'.$item->date->format('Y/m/d').'/'.$item->slug) }}" title="{{ $item->title }}">{{ $item->title }}</h2>
                        <h3>
                            <a href="{{ url('blog/'.$item->date->format('Y/m/d').'/'.$item->slug) }}">{{ $item->subtitle }}</a>
                        </h3>
                    </article>
                    @endforeach
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-7 col-xs-12 box-products">
            <header>
                <h2 class="title-home">Produtos</h2>
                <div class="customNavigation ws_controls">
                    <a href="{{ url('produtos') }}" class="all" title="Veja Mais">Veja Mais</a>
                </div>
            </header>
            <div class="clear">
                <div id="products" class="ws_gestures">
                    <div class="ws_thumbs hidden-sm hidden-xs">
                        <div>
                            @foreach($products as $product)
                            <a href="#" title="{{ $product->name }}">{{ $product->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="ws_images">
                        <div class="ws_list">
                            <ul>
                                @foreach($products as $key => $product)
                                <li><img src="{{ asset('assets/images/_upload/products/'.$product->image) }}" id="wows1_{{ $key }}" alt="{{ $product->name }}"></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12 box-work-with-us">
            <header>
                <h2 class="title-home">Trabalhe Conosco</h2>
            </header>
            <section class="work-with-us">
                <article>
                    <img src="{{ asset('assets/images/_upload/workWithUs/'.$workWithUsImage->text) }}" class="img-responsive" alt="Trabalhe Conosco" />
                    <h4>{{ $workWithUsHomeText->text }}</h4>
                </article>
                <div class="buttons">
                    <a href="{{ url('trabalhe-conosco') }}" class="btn btn-main" title="Vagas Disponíveis">Vagas Disponíveis</a>
                    <a href="{{ $workWithUsLink->text }}" target="_blank" class="btn btn-main" title="Enviar CV">Enviar CV</a>
                </div>
            </section>
        </div>
        <div class="calls hidden-sm hidden-xs">
            @foreach($calls as $call)
            <div class="col-lg-4 col-md-4">
                @if($call->url != "")
                <a href="{{ $call->url }}" target="{{ $call->target }}" title="{{ $call->title }}">
                @endif
                    <img src="{{ asset('assets/images/_upload/calls/'.$call->image) }}" title="{{ $call->title }}" alt="{{ $call->title }}" class="img-responsive">
                @if($call->url != "")
                </a>
                @endif
            </div>
            @endforeach
        </div>
        <div class="clear"></div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                <h3 class="title-home">Instagram</h3>
                <div class="margin-top-20 clear">
                    <!-- SnapWidget -->
                    <script src="http://snapwidget.com/js/snapwidget.js"></script>
                    <iframe src="http://snapwidget.com/in/?u=bGFidGV1dG98aW58MTAwfDN8Mnx8bm98MHxub25lfG9uU3RhcnR8eWVzfHllcw==&ve=050116" title="Instagram Widget" class="snapwidget-widget" allowTransparency="true" frameborder="0" scrolling="no" style="border:none; overflow:hidden; width:100%;"></iframe>
                </div>
                <div class="margin-top-15 clear">
                    <a href="{{ $websiteSettings['instagram'] }}" target="_blank" class="btn btn-main" title="Seguir">Seguir</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                <h3 class="title-home">Facebook</h3>
                <div class="margin-top-20 clear">
                    <div id="fb-root"></div>
                    <div class="fb-page" data-href="{{ $websiteSettings['facebook'] }}" data-height="250" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/espacofarmaceutico"><a href="https://www.facebook.com/espacofarmaceutico">Espaço Farmacêutico</a></blockquote></div></div>
                </div>
                <div class="margin-top-15 clear">
                    <a href="{{ $websiteSettings['facebook'] }}" target="_blank" class="btn btn-main" title="Seguir">Seguir</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 box-youtube">
                <h3 class="title-home">Youtube</h3>
                <div class="margin-top-20 clear">
                    <iframe width="100%" height="230" src="{{ $videoTheTeuto->text }}" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="margin-top-15 clear">
                    <a href="{{ $websiteSettings['youtube'] }}" target="_blank" class="btn btn-main" title="Seguir">Seguir</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/wow.slider.min.js') }}"></script>
<script>
$(document).ready(function() {
    $("#banners").owlCarousel({
        items : 3,
        itemsDesktop : [1000,3],
        itemsDesktopSmall : [900,2],
        itemsTablet: [600,2],
        itemsMobile : [599,1]
    });
    $("#news-and-releases").owlCarousel({
        items : 2,
        itemsDesktop : [1000,2],
        itemsDesktopSmall : [900,2],
        itemsTablet: [600,2],
        itemsMobile : [599,1],
        pagination : false
    });
    $("#news-and-releases-next").click(function(){
        $("#news-and-releases").trigger('owl.next');
    });
    $("#news-and-releases-prev").click(function(){
        $("#news-and-releases").trigger('owl.prev');
    });
    $("#blog").owlCarousel({
        items : 1,
        itemsDesktop : [1000,1],
        itemsDesktopSmall : [900,1],
        itemsTablet: [600,1],
        itemsMobile : [599,1],
        pagination : false
    });
    $("#blog-next").click(function(){
        $("#blog").trigger('owl.next');
    });
    $("#blog-prev").click(function(){
        $("#blog").trigger('owl.prev');
    });
});
wowReInitor(jQuery("#products"),{
    effect:"fade",
    prev:"Anterior",
    next:"Próximo",
    width: 300,
    height: 290,
    duration:1,
    delay:30*100,
    autoPlay:false,
    stopOnHover:true,
    loop:false,
    bullets:0,
    caption:false,
    controls:true,
    onBeforeStep:0,
    images:0
});
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v2.5";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
@stop