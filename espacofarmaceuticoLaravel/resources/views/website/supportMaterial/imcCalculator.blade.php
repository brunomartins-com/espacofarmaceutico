@extends('template.website')

@section('title')
{{ $pages->title." (Calculadora IMC) - " }}
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
                    <li><a href="{{ url('material-de-apoio/aplicativos') }}" title="Aplicativos">Aplicativos</a></li>
                    <li><a href="{{ url('material-de-apoio/calculadora-imc') }}" class="active" title="Calculadora IMC">Calculadora IMC</a></li>
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
            {!! Form::open([
                'id' => 'imc-calculator',
                'method' => 'post',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'url' => url('material-de-apoio/calculadora-imc')
                ])
            !!}
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::label('height', 'Altura') !!}
                        {!! Form::text('height', '', ['id'=>'height', 'class'=>'form-control input-main', 'data-mask'=>'0,00', 'data-mask-reverse'=>'true', 'placeholder'=>'Ex: 1,78', 'maxlength'=>4]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::label('weight', 'Peso') !!}
                        {!! Form::text('weight', '', ['id'=>'weight', 'class'=>'form-control input-main', 'data-mask'=>'#0,0', 'data-mask-reverse'=>'true', 'placeholder'=>'Ex: 74,00 ou 82,30', 'maxlength'=>5]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                    <div class="form-input text-right">
                        {!! Form::button('Enviar', ['class'=>'btn btn-main', 'type'=>'submit']) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            @if($imc)
            <p class="margin-top-35 strong font-size-18">O seu resultado é: {{ $imc }}</p>
            @endif
            <p class="margin-top-35">Tabela Comparativa:</p>
            <div class="row comparativeTable">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 strong">IMC</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 strong">Classificação</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Abaixo de 20</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">abaixo do peso</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">De 20 a 25</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">peso ideal</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">De 25 a 30</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">sobrepeso</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">De 30 a 35</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">obesidade moderada</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">De 35 a 40</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">obesidade severa</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">De 40 a 50</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">obesidade mórbida</div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Acima de 50</div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">superobesidade</div>
            </div>
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
$('#imc-calculator').validate({
    rules: {
        'height': {
            required: true
        },
        'weight': {
            required: true
        }
    },
    messages: {
        'height': {
            required: 'Informe sua altura'
        },
        'weight': {
            required: 'Informe seu peso'
        }
    }
});
</script>
@stop