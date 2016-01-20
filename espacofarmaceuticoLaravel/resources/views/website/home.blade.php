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
    <article class="item">
        <img src="{{ url('assets/images/_upload/banners/01.jpg') }}" alt="Concurso Um Novo Olhar" class="img-responsive" />
        <h2>Concurso Um Novo Olhar</h2>
        <h3>O concurso que irá te premiar com 3 iPhones 6.</h3>
        <a href="#" class="btn btn-main" title="Saiba Mais">Saiba Mais</a>
    </article>
    <article class="item">
        <img src="{{ url('assets/images/_upload/banners/02.jpg') }}" alt="Concurso Bebê Hipoderme Ômega" class="img-responsive" />
        <h2>Concurso Bebê Hipoderme Ômega</h2>
        <h3>Participe do concurso mais fofo do Brasil.</h3>
        <a href="#" class="btn btn-main" title="Saiba Mais">Saiba Mais</a>
    </article>
    <article class="item">
        <img src="{{ url('assets/images/_upload/banners/03.jpg') }}" alt="Novo vídeo 3D Prometazina" class="img-responsive" />
        <h2>Novo vídeo 3D Prometazina</h2>
        <h3>Veja toda a ação do medicamento.</h3>
        <a href="#" class="btn btn-main" title="Saiba Mais">Saiba Mais</a>
    </article>
    <article class="item">
        <img src="{{ url('assets/images/_upload/banners/04.jpg') }}" alt="Concurso Relax na Medida" class="img-responsive" />
        <h2>Concurso Relax na Medida</h2>
        <h3>Viagens para todo Brasil com o concurso relax</h3>
        <a href="#" class="btn btn-main" title="Saiba Mais">Saiba Mais</a>
    </article>
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
                    <article class="item">
                        <time>30/01/2015</time>
                        <h2><a href="#" title="Concurso fotográfico do Teuto valoriza a criatividade e a diversidade">Concurso fotográfico do Teuto valoriza a criatividade e a diversidade</h2>
                        <h3>
                            <a href="#">
                                Um Novo Olhar’ possibilita experiência diferenciada com a marca, valorizando formas únicas de ver o mundo; ação integra calendário online do laboratório e oferece, este ano, três iPhones 6
                            </a>
                        </h3>
                    </article>
                    <article class="item">
                        <time>30/01/2015</time>
                        <h2><a href="#" title="Com prêmios e interação, ações de endomarketing agitam a indústria farmacêutica">Com prêmios e interação, ações de endomarketing agitam a indústria farmacêutica</h2>
                        <h3>
                            <a href="#">
                                No Teuto, ‘Blitz do Marketing’ premia os colaboradores mensal e anualmente de acordo com os ‘10 Mandamentos da Qualidade’
                            </a>
                        </h3>
                    </article>
                    <article class="item">
                        <time>30/01/2015</time>
                        <h2><a href="#" title="Onde a crise não tem vez">Onde a crise não tem vez</h2>
                        <h3>
                            <a href="#">
                                Apesar do pessimismo sobre a economia brasileira, cada vez mais empresários do varejo farmacêutico aproveitam o momento para expandir os negócios e abrir novas lojas pelo Brasil
                            </a>
                        </h3>
                    </article>
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
                    <article class="item">
                        <time>30/01/2015</time>
                        <h2><a href="#" title="Por que investir em treinamentos da equipe?">Por que investir em treinamentos da equipe?</h2>
                        <h3>
                            <a href="#">
                                Um Novo Olhar’ possibilita experiência diferenciada com a marca, valorizando formas únicas de ver o mundo; ação integra calendário online do laboratório e oferece, este ano, três iPhones 6
                            </a>
                        </h3>
                    </article>
                    <article class="item">
                        <time>27/01/2015</time>
                        <h2><a href="#" title="Concurso fotográfico do Teuto valoriza a criatividade e a diversidade">Concurso fotográfico do Teuto valoriza a criatividade e a diversidade</h2>
                        <h3>
                            <a href="#">
                                Diante das turbulências do setor e incertezas do mercado, muitos gestores têm se perguntado se vale mesmo a pena investir em treinamentos, considerando principalmente que os empregados nos dias de hoje não têm pela empresa
                            </a>
                        </h3>
                    </article>
                    <article class="item">
                        <time>30/01/2015</time>
                        <h2><a href="#" title="Com prêmios e interação, ações de endomarketing agitam a indústria farmacêutica">Com prêmios e interação, ações de endomarketing agitam a indústria farmacêutica</h2>
                        <h3>
                            <a href="#">
                                No Teuto, ‘Blitz do Marketing’ premia os colaboradores mensal e anualmente de acordo com os ‘10 Mandamentos da Qualidade’
                            </a>
                        </h3>
                    </article>
                    <article class="item">
                        <time>30/01/2015</time>
                        <h2><a href="#" title="Onde a crise não tem vez">Onde a crise não tem vez</h2>
                        <h3>
                            <a href="#">
                                Apesar do pessimismo sobre a economia brasileira, cada vez mais empresários do varejo farmacêutico aproveitam o momento para expandir os negócios e abrir novas lojas pelo Brasil
                            </a>
                        </h3>
                    </article>
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
                            <a href="#" title="Aerodini">Aerodini</a>
                            <a href="#" title="Viasil">Viasil</a>
                            <a href="#" title="Triaxon">Triaxon</a>
                            <a href="#" title="Tetraderm">Tetraderm</a>
                            <a href="#" title="Genéricos Teuto">Genéricos Teuto</a>
                            <a href="#" title="Captopril">Captopril</a>
                            <a href="#" title="Carbamazepina">Carbamazepina</a>
                            <a href="#" title="Carbosisteína">Carbosisteína</a>
                        </div>
                    </div>
                    <div class="ws_images">
                        <div class="ws_list">
                            <ul>
                                <li><img src="{{ asset('assets/images/_upload/products/2013030483052_1ba95f43048bdd9d2ea4fce4ddb8c395.jpg') }}" id="wows1_0" alt="Aerodini"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/2013031581041_ea74f4155e621a7428036ec09975ea3f.jpg') }}" id="wows1_1" alt="Viasil"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/2013031584252_c9a451e9d303f11798a96f7e17cc95d8.jpg') }}" id="wows1_2" alt="Triaxon"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/2013031584445_84c22e7aef01dd22104e534758a37d4b.jpg') }}" id="wows1_3" alt="Tetraderm"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/20130221151647_a51701be307c105bebabfb6c00b15ed4.jpg') }}" id="wows1_4" alt="Genéricos Teuto"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/20130221151939_58cb05bf25f3d1ce86ac45ab80a6fe7c.jpg') }}" id="wows1_5" alt="Captopril"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/20130221151954_b64c40a3fe7e4cdc4a24693851f60a14.jpg') }}" id="wows1_6" alt="Carbamazepina"></li>
                                <li><img src="{{ asset('assets/images/_upload/products/20130221152013_2f39492d4e3b2c1280989eef9a54622a.jpg') }}" id="wows1_7" alt="Carbosisteína"></li>
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
                    <img src="{{ asset('assets/images/work-with-us.jpg') }}" class="img-responsive" alt="Trabalhe Conosco" />
                    <h4>
                        Considerado um dos principais fatores para o sucesso e uma das mais importantes vantagens competitivas da empresa, o reconhecimento do potencial humano constitui preocupação constante e uma forma de valorização dos profissionais.
                    </h4>
                </article>
                <div class="buttons">
                    <a href="#" class="btn btn-main" title="Vagas Disponíveis">Vagas Disponíveis</a>
                    <a href="#" class="btn btn-main" title="Enviar CV">Enviar CV</a>
                </div>
            </section>
        </div>
        <div class="calls hidden-sm hidden-xs">
            <div class="col-lg-4 col-md-4">
                <a href="#"><img src="{{ asset('assets/images/_upload/calls/01.jpg') }}" alt="EQ's" class="img-responsive"></a>
            </div>
            <div class="col-lg-4 col-md-4">
                <a href="#"><img src="{{ asset('assets/images/_upload/calls/02.jpg') }}" alt="Teuto 360º" class="img-responsive"></a>
            </div>
            <div class="col-lg-4 col-md-4">
                <a href="#"><img src="{{ asset('assets/images/_upload/calls/03.jpg') }}" alt="Vídeos 3D" class="img-responsive"></a>
            </div>
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
                    <a href="#" target="_blank" class="btn btn-main" title="Seguir">Seguir</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 hidden-xs">
                <h3 class="title-home">Facebook</h3>
                <div class="margin-top-20 clear">
                    <div id="fb-root"></div>
                    <div class="fb-page" data-href="https://www.facebook.com/espacofarmaceutico" data-height="250" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/espacofarmaceutico"><a href="https://www.facebook.com/espacofarmaceutico">Espaço Farmacêutico</a></blockquote></div></div>
                </div>
                <div class="margin-top-15 clear">
                    <a href="#" target="_blank" class="btn btn-main" title="Seguir">Seguir</a>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 box-youtube">
                <h3 class="title-home">Youtube</h3>
                <div class="margin-top-20 clear">
                    <iframe width="100%" height="230" src="https://www.youtube.com/embed/ZQTm8G4b8j4" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="margin-top-15 clear">
                    <a href="#" target="_blank" class="btn btn-main" title="Seguir">Seguir</a>
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