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
                'id' => 'profile',
                'method' => 'put',
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data',
                'url' => url('meus-dados')
                ])
            !!}
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        <label for="nome" class="text-orange padding-left-5">Nome</label>
                        {!! Form::text('name', Auth::getUser()->name, ['id'=>'name', 'class'=>'form-control input-main', 'placeholder'=>'Nome:', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        <label for="email" class="text-orange padding-left-5">E-mail</label>
                        {!! Form::email('email', Auth::getUser()->email, ['id'=>'email', 'class'=>'form-control input-main', 'maxlength'=>40, 'readonly'=>true]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
                    <div class="form-input">
                        <label for="crf" class="text-orange padding-left-5">CRF</label>
                        {!! Form::text('crf', Auth::getUser()->crf, ['id'=>'crf', 'class'=>'form-control input-main', 'placeholder'=>'CRF', 'maxlength'=>30]) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12 padding-top-7">
                    <div class="form-input padding-top-20 text-orange font-size-14">
                        {!! Form::label('gender', 'Sexo:', ['class'=>'margin-right-10']) !!}
                        <label class="radio-check margin-right-20">
                            {!! Form::radio('gender', 'Masculino', $checkedGenderMale) !!}
                            Mas.
                        </label>
                        <label class="radio-check">
                            {!! Form::radio('gender', 'Feminino', $checkedGenderFemale) !!}
                            Fem.
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
                    <div class="form-input">
                        <label for="birthDate" class="text-orange padding-left-5">Data nascimento</label>
                        {!! Form::text('birthDate', $birthDate, ['id'=>'birthDate', 'class'=>'form-control input-main', 'data-mask'=>'00/00/0000', 'placeholder'=>'Data Nascimento', 'maxlength'=>10]) !!}
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <p class="font-size-14 normal">Gostaria de receber informações via e-mail? Após o primeiro e-mail, você poderá optar por não receber mais.</p>
                    <div class="form-input padding-top-7 text-orange font-size-14">
                        <label class="radio-check margin-right-20">
                            {!! Form::radio('newsletter', 1, $checkedNewsletterYes) !!}
                            Sim
                        </label>
                        <label class="radio-check">
                            {!! Form::radio('newsletter', 0, $checkedNewsletterNo) !!}
                            Não
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="col-xs-12">
                    <label for="password" class="text-orange padding-left-5">Preencha caso deseje alterar sua senha</label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-input">
                        <input name="password" id="password" type="password" class="form-control input-main" placeholder="Nova Senha" maxlength="16">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-input">
                        <input name="password_confirmation" id="password_confirmation" type="password" class="form-control input-main" placeholder="Confirmar Senha" maxlength="16">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::button('Alterar', ['class'=>'btn btn-main pull-right', 'type'=>'submit']) !!}
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
$('#profile').validate({
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
        'newsletter': {
            required: true
        },
        'password': {
            minlength: 6,
            maxlength: 16
        },
        'password_confirmation': {
            minlength: 6,
            maxlength: 16,
            equalTo:"#profile #password"
        }
    }
});
</script>
@stop