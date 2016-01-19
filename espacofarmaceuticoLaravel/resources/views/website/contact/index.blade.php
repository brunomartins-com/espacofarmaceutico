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
        <section class="col-lg-8 col-md-8 col-sm-12 col-xs-12 text margin-bottom-30 pull-right">
            @if (Session::has('success'))
            <div class="alert alert-main alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {!! Session::get('success') !!}
            </div>
            @endif
            @if (isset($errors) and count($errors) > 0)
            <div class="col-lg-12 col-xs-12 alert alert-danger margin-top-10">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {!! $text->text !!}

            {!! Form::open([
                'id' => 'contact',
                'method' => 'post',
                'class' => 'form-horizontal margin-top-30',
                'enctype' => 'multipart/form-data',
                'url' => url('contato')
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
                        {!! Form::email('email', '', ['id'=>'email', 'class'=>'form-control input-main', 'placeholder'=>'E-mail:', 'maxlength'=>40]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">
                    <div class="form-input">
                        {!! Form::textarea('message', '', ['id'=>'message', 'class'=>'form-control input-main', 'rows'=>8, 'placeholder'=>'Mensagem:']) !!}
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                    <div class="form-input text-right">
                        {!! Form::button('Enviar', ['class'=>'btn btn-main', 'type'=>'submit']) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
        <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12 pull-left">
            <section class="costumer-service">
                <p>Serviço de Atendimento ao Consumidor</p>
                <p>{{ $websiteSettings['costumerServicePhone'] }}</p>
                <p>{{ $websiteSettings['email'] }}</p>
            </section>
            <section class="location">
                <p>Laboratório Teuto Brasileiro S/A</p>
                <p>{{ $websiteSettings['address'] }}</p>
                <p>CEP: 75132-140 - Anápolis/GO</p>
                <p>Telefone: {{ $websiteSettings['phone'] }}</p>
            </section>
        </aside>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 navigation-bottom">
            <a href="{{ url('/') }}" class="btn btn-main" title="Home">Home</a>
            <a href="#" class="btn btn-main btn-topo" title="Topo">Topo</a>
        </div>
    </div>
</div>
@stop

@section('javascript')
<script>
//VALIDATE FORM
$('#contact').validate({
    rules: {
        'name': {
            required: true
        },
        'email': {
            required: true,
            email: true
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
        'message': {
            required: 'Escreva uma mensagem'
        }
    }
});
</script>
@stop