@extends('template.website')

@section('title')
{{ $pages->title." (Agende sua Visita) - " }}
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
                    <li><a href="{{ url('visite-bem') }}" title="Sobre o Visite Bem">Sobre o Visite Bem</a></li>
                    <li><a href="{{ url('visite-bem/fotos') }}" title="Fotos">Fotos</a></li>
                    <li><a href="{{ url('visite-bem/agende-sua-visita') }}" class="active" title="Agende sua visita">Agende sua visita</a></li>
                </ul>
            </nav>
        </aside>
        <article class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text margin-bottom-30">
            @if (Session::has('success'))
            <div class="alert alert-main alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! Session::get('success') !!}
            </div>
            @endif
            @if (isset($errors) and count($errors) > 0)
            <div class="col-lg-12 col-xs-12 alert alert-danger margin-top-15">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {!! $text->text !!}

            {!! Form::open([
                'id' => 'schedule-your-visit',
                'method' => 'post',
                'class' => 'form-horizontal margin-top-30',
                'enctype' => 'multipart/form-data',
                'url' => url('visite-bem/agende-sua-visita')
                ])
            !!}
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::text('name', '', ['id'=>'name', 'class'=>'form-control input-main', 'placeholder'=>'Nome:', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::email('email', '', ['id'=>'email', 'class'=>'form-control input-main', 'placeholder'=>'E-mail:', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::text('companyFoundation', '', ['id'=>'companyFoundation', 'class'=>'form-control input-main', 'placeholder'=>'Empresa/Institucional:', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-9">
                    <div class="form-input">
                        {!! Form::select('state', $states, '', ['id'=>'state', 'class'=>'form-control input-main']) !!}
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-7 col-xs-12">
                    <div class="form-input">
                        {!! Form::select('city', [''=>'Escolha o Estado primeiro'], '', ['id'=>'city', 'class'=>'form-control input-main']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9">
                    <div class="form-input">
                        {!! Form::text('phone', '', ['id'=>'phone', 'class'=>'form-control input-main', 'placeholder'=>'Tel. Fixo:', 'data-mask'=>'(00) 0000-0000', 'maxlength'=>14]) !!}
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-9">
                    <div class="form-input">
                        {!! Form::text('mobile', '', ['id'=>'mobile', 'class'=>'form-control input-main', 'placeholder'=>'Tel. Celular:', 'data-mask'=>'(00) 0000-00009', 'maxlength'=>15]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                    <div class="form-input">
                        {!! Form::textarea('message', '', ['id'=>'message', 'class'=>'form-control input-main', 'rows'=>5, 'placeholder'=>'Mensagem:']) !!}
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                    <div class="form-input text-right">
                        {!! Form::button('Enviar', ['class'=>'btn btn-main', 'type'=>'submit']) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </article>
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

@section('javascript')
<script>
//VALIDATE FORM
$('#schedule-your-visit').validate({
    rules: {
        'name': {
            required: true
        },
        'email': {
            required: true,
            email: true
        },
        'companyFoundation': {
            required: true
        },
        'state': {
            required: true
        },
        'city': {
            required: true
        },
        'phone': {
            required: function(element){
                if($("#mobile").val() == ''){
                    return true;
                }else{
                    return false;
                }
            }
        },
        'mobile': {
            required: function(element){
                if($("#phone").val() == ''){
                    return true;
                }else{
                    return false;
                }
            }
        },
        'message': {
            required: true
        }
    },
    messages: {
        'name': {
            required: 'Informe seu nome'
        },
        'email': {
            required: 'Informe seu e-mail',
            email: 'Informe um e-mail válido'
        },
        'companyFoundation': {
            required: 'Informe a empresa/instituição'
        },
        'state': {
            required: 'Escolha o seu Estado'
        },
        'city': {
            required: 'Escolha a sua cidade'
        },
        'phone': {
            required: 'Informe um número de telefone'
        },
        'mobile': {
            required: 'ou um celular'
        },
        'message': {
            required: 'Escreva uma mensagem'
        }
    }
});
</script>
@stop