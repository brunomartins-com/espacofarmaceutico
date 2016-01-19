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
    <section class="col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0 col-sm-12 col-xs-12 text-page">
        <header>
            <h2 class="title pc80">{{ $pages->title }}</h2>
            <div class="box-share pc20 hidden-xs">
                @include('website.print-text-size')
            </div>
        </header>
        <aside class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <h3 class="text-orange font-size-24">{{ $pages->description }}</h3>
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
                'id' => 'registration',
                'method' => 'post',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'url' => url('cadastre-se')
                ])
            !!}
            {!! Form::hidden('type', 1) !!}
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
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
                    <div class="form-input">
                        {!! Form::text('crf', '', ['id'=>'crf', 'class'=>'form-control input-main', 'placeholder'=>'CRF', 'maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                    <div class="form-input padding-top-7 text-orange font-size-14">
                        {!! Form::label('gender', 'Sexo:', ['class'=>'margin-right-10']) !!}
                        <label class="radio-check margin-right-20">
                            {!! Form::radio('gender', 'Masculino') !!}
                            Mas.
                        </label>
                        <label class="radio-check">
                            {!! Form::radio('gender', 'Feminino') !!}
                            Fem.
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
                    <div class="form-input">
                        {!! Form::text('birthDate', '', ['id'=>'birthDate', 'class'=>'form-control input-main', 'data-mask'=>'00/00/0000', 'placeholder'=>'Data Nascimento', 'maxlength'=>10]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                    <div class="form-input">
                        {!! Form::text('zipCode', '', ['id'=>'zipCode', 'class'=>'form-control input-main', 'data-mask'=>'00000000', 'placeholder'=>'CEP', 'maxlength'=>8]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::text('address', '', ['id'=>'address', 'class'=>'form-control input-main', 'placeholder'=>'Endereço', 'maxlength'=>200]) !!}
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
            <hr>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <p class="font-size-14 normal">Gostaria de receber informações via e-mail? Após o primeiro e-mail, você poderá optar por não receber mais.</p>
                    <div class="form-input padding-top-7 text-orange font-size-14">
                        <label class="radio-check margin-right-20">
                            {!! Form::radio('newsletter', 1) !!}
                            Sim
                        </label>
                        <label class="radio-check">
                            {!! Form::radio('newsletter', 0) !!}
                            Não
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-input">
                        <input name="password" id="password" type="password" class="form-control input-main" placeholder="Senha" maxlength="16">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-input">
                        <input name="password_confirmation" id="password_confirmation" type="password" class="form-control input-main" placeholder="Confirmar Senha" maxlength="16">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::button('Cadastrar', ['class'=>'btn btn-main pull-right', 'type'=>'submit']) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </article>
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
$('#registration').validate({
    rules: {
        'name': {
            maxlength: 100,
            required: true
        },
        'email': {
            maxlength: 40,
            required: true,
            email: true
        },
        'crf': {
            maxlength: 30,
            required: true
        },
        'gender': {
            required: true
        },
        'birthDate': {
            dateBR: true,
            maxlength: 10,
            required: true
        },
        'cep': {
            maxlength: 8,
            required: true
        },
        'address': {
            maxlength: 200,
            required: true
        },
        'state': {
            required: true
        },
        'city': {
            required: true
        },
        'newsletter': {
            required: true
        },
        'password': {
            required: true,
            minlength: 6,
            maxlength: 16
        },
        'password_confirmation': {
            required:true,
            minlength: 6,
            maxlength: 16,
            equalTo:"#registration #password"
        }
    }
});
</script>
@stop