@if($websiteSettings['websiteOk'] == 0 and !Auth::check())
{!! view('website.teaser')->with(compact('page', 'websiteSettings')) !!}
{{ die }}
@endif
<!--
Project: Espaço Farmacêutico
Author: Bruno Martins - Web Developer
Website: www.brunomartins.com
Contact: hello@brunomartins.com
-->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no" />
    <title>@yield('title'){{ $websiteSettings['title'] }}</title>
    <link rel="shortcut icon" href="{!! asset('assets/images/_upload/dados-do-site/'.$websiteSettings['favicon']) !!}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/_upload/dados-do-site/'.$websiteSettings['appleTouchIcon']) }}" />
    {!! $websiteSettings['googleAnalytics'] !!}
    <!-- Meta Tags -->
    <meta name="title" content="@yield('title'){{ $websiteSettings['title'] }}" />
    <meta name="geography" content="Brazil">
    <meta name="language" content="Português Brasil">
    <meta name="revisit-after" content="30 days">
    <meta name="distribution" content="Brasil">
    <meta name="country" content="Brasil, BRA">
    <meta property="og:determiner" content="brasil">
    <meta property="og:locale" content="pt_BR">
    <meta name="robots" content="index,follow">
    <meta name="location" content="Anápolis,Goiás">
    <meta name="rating" content="General">
    <meta name="author" content="Teuto | Pfizer">
    <meta name="geo.position" content="GO">
    <meta name="geo.placename" content="Anápolis">
    <meta name="geo.region" content="br">
    <!-- Tags Facebook -->
    <meta property="og:title" content="@yield('title'){{ $websiteSettings['title'] }}" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="pt_BR" />
    <meta property="og:url" content="{{ \URL::current() }}" />
    <meta property="og:image" content="{{ asset('assets/images/_upload/dados-do-site/'.$websiteSettings['avatar']) }}" />
    <meta property="og:site_name" content="@yield('title'){{ $websiteSettings['title'] }}" />

    @yield('head')

</head>
<body class="{{ $page }}">
<div class="menu-top">
    <div class="container">
        <div class="col-xs-12">
            <ul class="pull-right">
                <li><a href="http://www.teuto.com.br" target="_blank">Teuto</a></li>
                <li><a href="http://www.drteuto.com.br" target="_blank">Dr. Teuto</a></li>
                <li class="active margin-right-0"><a href="/" title="Espaço Farmacêutico">Espaço Farmacêutico</a></li>
                <li class="hidden-xs"><a href="http://teuto.com.br/aplicativo" target="_blank" title="Aplicativo Força de Vendas">Aplicativo Força de Vendas</a></li>
            </ul>
        </div>
    </div>
</div>
<header id="header">
    <div class="container">
        <section class="col-lg-2 col-md-3 col-sm-3 col-xs-3">
            <h1 class="logotype" onclick="window.open('{{ url('/') }}', '_self');">Espaço Farmacêutico</h1>
        </section>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-3">
            <a href="#" title="Menu" class="btn-nav">Menu</a>
        </div>
        <div class="col-lg-2 col-lg-offset-0 col-md-2 col-md-offset-1 col-sm-2 col-sm-offset-0 col-xs-4 col-xs-offset-0">
            @if(!Auth::check() or Auth::getUser()->type != 1)
            <a href="#" title="Login" class="btn btn-main btn-block btn-login">Login</a>
            @endif
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 hidden-xs">
            @if(!Auth::check() or Auth::getUser()->type != 1)
            <a href="{{ url('cadastre-se') }}" title="Cadastrar" class="btn btn-main btn-block">Cadastrar</a>
            @else
            <a href="#" title="Bem Vindo {{ Auth::getUser()->name }}" class="btn btn-main btn-block btn-user-data"><span class="glyphicon glyphicon-user"></span></a>
            @endif
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 hidden-xs">
            <a href="{{ url('conselhos-regionais') }}" class="font-size-12 text-orange" title="Conselhos Regionais">Conselhos Regionais</a>
            <br>
            <a href="{{ url('farmacovigilancia') }}" class="font-size-12 text-orange" title="Farmacovigilância">Farmacovigilância</a>
            <br>
            <a href="{{ url('trabalhe-conosco') }}" class="font-size-12 text-orange" title="Trabalhe Conosco">Trabalhe Conosco</a>
        </div>
        <div class="col-lg-2 hidden-md hidden-sm hidden-xs">
            <ul class="social-network">
                <li><a href="https://www.facebook.com/espacofarmaceutico" target="_blank" class="facebook" title="Teuto no Facebook">Teuto no Facebook</a></li>
                <li><a href="http://www.twitter.com/espfarmaceutico" target="_blank" class="twitter" title="Teuto no Twitter">Teuto no Twitter</a></li>
                <li><a href="http://www.youtube.com/user/labteutopfizer" target="_blank" class="youtube" title="Teuto no LinkedIn">Teuto no YouTube</a></li>
                <li><a href="http://www.instagram.com/labteuto" target="_blank" class="instagram" title="Teuto no Instagram">Teuto no Instagram</a></li>
                <li><a href="http://www.linkedin.com/company/labteuto" target="_blank" class="linkedin" title="Teuto no LinkedIn">Teuto no LinkedIn</a></li>
            </ul>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
            <a href="#" title="Buscar" class="btn btn-main btn-search pull-right"><span class="glyphicon glyphicon-search"></span></a>
        </div>
    </div>
</header>
<div class="navigation">
    <div class="container">
        <span class="col-lg-1 col-lg-offset-2 col-md-1 col-md-offset-3 col-sm-1 col-sm-offset-3 col-xs-3 col-xs-offset-3 arrow"></span>
        <nav>
            <ul class="col-lg-3 col-lg-offset-1 col-md-3 col-md-offset-0 col-sm-6 col-xs-12">
                <li><a href="{{ url('o-teuto') }}" title="O Teuto">O Teuto</a></li>
                <li><a href="{{ url('medicamentos-genericos') }}" title="Medicamentos Genéricos">Medicamentos Genéricos</a></li>
                <li><a href="{{ url('blog') }}" title="Blog">Blog</a></li>
                <li><a href="{{ url('noticias-e-releases') }}" title="Notícias e Releases">Notícias e Releases</a></li>
            </ul>
            <ul class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <li><a href="{{ url('universidade-teuto') }}" title="Universidade Teuto">Universidade Teuto</a></li>
                <li><a href="{{ url('visite-bem') }}" title="Visite Bem">Visite Bem</a></li>
                <li><a href="{{ url('material-de-apoio') }}" title="Material de Apoio">Material de Apoio</a></li>
                <li><a href="{{ url('contato') }}" title="Contato">Contato</a></li>
            </ul>
            <span class="col-lg-5 col-lg-offset-0 col-md-5 col-md-offset-1 col-sm-12 col-xs-12 font-size-12 text-orange-yellow">Menu Acesso Restrito</span>
            <ul class="col-lg-2 col-md-3 col-sm-6 col-xs-12 orange-yellow">
                <li><a href="{{ url('eventos') }}" title="Eventos">Eventos</a></li>
                <li><a href="{{ url('produtos') }}" title="Produtos">Produtos</a></li>
                <li class="hidden-xs"><a href="{{ url('instituto-bulla') }}" title="Instituto Bulla">Instituto Bulla</a></li>
                <li class="visible-xs"><a href="{{ url('videos-3d') }}" title="Vídeos 3D">Vídeos 3D</a></li>
                <li class="visible-xs"><a href="{{ url('seu-negocio-mais-lucrativo') }}" title="Seu Negócio Mais Lucrativo">Seu Negócio Mais Lucrativo</a></li>
            </ul>
            <ul class="col-lg-3 col-md-3 col-sm-6 hidden-xs orange-yellow">
                <li><a href="{{ url('videos-3d') }}" title="Vídeos 3D">Vídeos 3D</a></li>
                <li><a href="{{ url('seu-negocio-mais-lucrativo') }}" title="Seu Negócio Mais Lucrativo">Seu Negócio Mais Lucrativo</a></li>
            </ul>
        </nav>
        <!-- LOGIN -->
        <section class="login">
            {!! Form::open([
                'id' => 'form-login',
                'method' => 'post',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'url' => url('login')
                ])
            !!}
            {!! Form::hidden('type', 1) !!}
            <h3 class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-0 col-sm-4 col-xs-12">Faça aqui seu login e tenha acesso exclusivo a conteúdos, notícias, eventos e vídeos 3D.</h3>
            <p class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                {!! Form::label('email', 'E-mail:') !!}
                {!! Form::email('email', '', ['class'=>'form-control', 'id'=>'email']) !!}
            </p>
            <p class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                {!! Form::label('password', 'Senha:') !!}
                <input type="password" name="password" id="password" class="form-control" />
                <a href="#" id="forgot-password" class="text-orange-yellow font-size-11" title="Esqueceu sua senha?">Esqueceu sua senha?</a>
            </p>
            <p class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                {!! Form::button('Entrar', ['class'=>'btn btn-main btn-block', 'type'=>'submit']) !!}
            </p>
            {!! Form::close() !!}
            <div class="col-xs-12 visible-xs no-registered">
                <p>Não sou cadastrado.</p>
                <a href="{{ url('cadastre-se') }}" class="btn btn-main" title="Cadastrar">Cadastrar</a>
            </div>
        </section>
        <!-- LOGIN -->
        <section class="forgot-password">
            {!! Form::open([
                'id' => 'form-recovery-password',
                'method' => 'post',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'url' => url('esqueceu-sua-senha')
                ])
            !!}
            <h3 class="col-lg-4 col-lg-offset-1 col-md-4 col-md-offset-0 col-sm-4 col-xs-12">Informe seu e-mail para a recuperação do seu acesso.</h3>
            <p class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                {!! Form::label('email', 'E-mail:') !!}
                {!! Form::email('email', '', ['class'=>'form-control', 'id'=>'email']) !!}
                <a href="#" id="return-login" class="text-orange-yellow font-size-11" title="Voltar">Voltar</a>
            </p>
            <p class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                {!! Form::button('Enviar', ['class'=>'btn btn-main btn-block', 'type'=>'submit']) !!}
            </p>
            {!! Form::close() !!}
        </section>
        @if(Auth::check() and Auth::getUser()->type == 1)
        <!-- USER DATA -->
        <section class="user-data">
            <div class="col-lg-6 col-lg-offset-1 col-md-8 col-md-offset-0 col-sm-9 col-xs-12">
                <h3>Olá, Sr.(a) {{ Auth::getUser()->name }}</h3>
                <ul>
                    <li><a href="{{ url('meus-dados') }}" title="Meus Dados">Meus Dados</a></li>
                    <li><a href="{{ url('meu-endereco') }}" title="Meu Endereço">Meu Endereço</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-3 col-xs-12">
                <a href="{{ url('sair') }}" class="btn btn-block btn-main" title="Sair">Sair</a>
            </div>
        </section>
        @endif
        <!-- SEARCH -->
        <section class="search">
            {!! Form::open([
                'id' => 'search',
                'method' => 'post',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'url' => url('busca')
                ])
            !!}
            <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12 col-xs-12">
                {!! Form::label('inputSearch', 'Faça a busca por aqui:', ['class'=>'hidden-xs']) !!}
                <div class="input-group">
                    {!! Form::text('inputSearch', '', ['class'=>'form-control', 'id'=>'inputSearch', 'placeholder'=>'Buscar']) !!}
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-main"><span class="glyphicon glyphicon-search"></button>
                    </span>
                </div>
            </p>
            {!! Form::close() !!}
        </section>
    </div>
</div>

@yield('content')

<footer id="footer">
    <div class="container">
        <ul class="col-lg-2 col-lg-offset-1 col-md-3 col-md-offset-1 col-sm-3 col-sm-offset-0 col-xs-7">
            <li><a href="{{ url('o-teuto') }}" title="O Teuto">O Teuto</a></li>
            <li><a href="{{ url('medicamentos-genericos') }}" title="Medicamentos Genéricos">Medicamentos Genéricos</a></li>
            <li><a href="{{ url('produtos') }}" title="Produtos">Produtos</a></li>
            <li><a href="{{ url('treinamentos') }}" title="Treinamentos">Treinamentos</a></li>
            <li><a href="{{ url('blog') }}" title="Blog">Blog</a></li>
            <li><a href="{{ url('noticias-e-releases') }}" title="Notícias e Releases">Notícias e Releases</a></li>
        </ul>
        <ul class="col-lg-2 col-md-2 col-sm-3 col-sm-offset-0 col-xs-5 remove-padding-r">
            <li><a href="{{ url('artigos') }}" title="Artigos">Artigos</a></li>
            <li><a href="{{ url('material-de-apoio') }}" title="Material de Apoio">Material de Apoio</a></li>
            <li><a href="{{ url('eventos') }}" title="Eventos">Eventos</a></li>
            <li><a href="{{ url('entretenimentos') }}" title="Entretenimentos">Entretenimentos</a></li>
            <li><a href="{{ url('videos') }}" title="Vídeos">Vídeos</a></li>
            <li><a href="{{ url('contato') }}" title="Contato">Contato</a></li>
        </ul>
        <div class="col-lg-3 col-md-3 col-sm-4 col-sm-offset-0 col-xs-12">
            <ul class="social-network">
                <li><a href="https://www.facebook.com/espacofarmaceutico" target="_blank" class="facebook" title="Teuto no Facebook">Teuto no Facebook</a></li>
                <li><a href="http://www.twitter.com/espfarmaceutico" target="_blank" class="twitter" title="Teuto no Twitter">Teuto no Twitter</a></li>
                <li><a href="http://www.youtube.com/user/labteutopfizer" target="_blank" class="youtube" title="Teuto no Youtube">Teuto no YouTube</a></li>
                <li><a href="http://www.instagram.com/labteuto" target="_blank" class="instagram" title="Teuto no Instagram">Teuto no Instagram</a></li>
                <li><a href="http://www.linkedin.com/company/labteuto" target="_blank" class="linkedin" title="Teuto no LinkedIn">Teuto no LinkedIn</a></li>
            </ul>
            <div class="odd-links">
                <a href="{{ url('conselhos-regionais') }}" class="font-size-11 text-white" title="Conselhos Regionais">Conselhos Regionais</a>
                <a href="{{ url('farmacovigilancia') }}" class="font-size-11 text-white text-right" title="Farmacovigilância">Farmacovigilância</a>
                <a href="{{ url('trabalhe-conosco') }}" class="font-size-11 text-white" title="Trabalhe Conosco">Trabalhe Conosco</a>
            </div>
        </div>
        <div class="col-lg-2 col-lg-offset-1 col-md-2 col-md-offset-0 col-sm-2 col-sm-offset-0 col-xs-12 teuto-pfizer-groodee">
            <a href="http://www.teuto.com.br" target="_blank" class="teuto-pfizer" title="Teuto | Pfizer">Teuto | Pfizer</a>
            <br>
            <a href="http://www.groodee.com" target="_blank" class="groodee" title="Groodee">Groodee</a>
        </div>
    </div>
</footer>
<link rel="stylesheet" href="{!! asset('assets/css/bootstrap.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('assets/css/main.css') !!}" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic" />
<script type="text/javascript" src="{!! asset('assets/js/jquery.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/js/main.min.js') !!}"></script>
@if(Session::has('message'))
<script>
alert('{!! Session::get('message') !!}');
</script>
@endif
@yield('javascript')
</body>
</html>