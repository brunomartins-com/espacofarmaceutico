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
                'url' => url('meu-endereco')
                ])
            !!}
            <div class="form-group">
                <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                    <div class="form-input">
                        <label class="text-orange padding-left-5">CEP</label>
                        {!! Form::text('zipCode', Auth::getUser()->zipCode, ['id'=>'zipCode', 'class'=>'form-control input-main', 'data-mask'=>'00000000', 'placeholder'=>'CEP', 'maxlength'=>8]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        <label class="text-orange padding-left-5">Endereço</label>
                        {!! Form::text('address', Auth::getUser()->address, ['id'=>'address', 'class'=>'form-control input-main', 'placeholder'=>'Endereço', 'maxlength'=>200]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                    <div class="form-input">
                        <label class="text-orange padding-left-5">Estado</label>
                        {!! Form::select('state', $states, Auth::getUser()->state, ['id'=>'state', 'class'=>'form-control input-main']) !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-7 col-xs-12">
                    <div class="form-input">
                        <label class="text-orange padding-left-5">Cidade</label>
                        {!! Form::select('city', $cities, Auth::getUser()->city, ['id'=>'city', 'class'=>'form-control input-main']) !!}
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
        }
    }
});
</script>
@stop