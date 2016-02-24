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
                    <div class="form-input padding-top-7 text-orange font-size-14">
                        {!! Form::label('peopleType', 'Pessoa:', ['class'=>'margin-right-10']) !!}
                        <label class="radio-check margin-right-20">
                            {!! Form::radio('peopleType', 'Física') !!}
                            Física
                        </label>
                        <label class="radio-check">
                            {!! Form::radio('peopleType', 'Jurídica') !!}
                            Jurídica
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        {!! Form::text('name', '', ['id'=>'name', 'class'=>'form-control input-main', 'placeholder'=>'Nome:', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group fisical-people hidden">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                    <div class="form-input">
                        <select name="youAre" id="youAre" class="form-control input-main">
                            <option value="">Informe...</option>
                            <option value="Estudante/Acadêmico/Universidade">Estudante/Acadêmico/Universidade</option>
                            <option value="Consumidor/Paciente/Familiar">Consumidor/Paciente/Familiar</option>
                            <option value="Clínica/Hospital">Clínica/Hospital</option>
                            <option value="Distribuidor/Farmácia">Distribuidor/Farmácia</option>
                            <option value="Entidade Governamental">Entidade Governamental</option>
                            <option value="Imprensa">Imprensa</option>
                            <option value="Organização não Governamental (ONG)">Organização não Governamental (ONG)</option>
                            <option value="Prestador de Serviço">Prestador de Serviço</option>
                            <option value="Fornecedor de Produto">Fornecedor de Produto</option>
                            <option value="Profissional de Saúde">Profissional de Saúde</option>
                            <option value="Outros não Listados">Outros não Listados</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group fisical-people hidden">
                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-10">
                    <div class="form-input">
                        {!! Form::text('cpf', '', ['id'=>'cpf', 'class'=>'form-control input-main', 'data-mask'=>'000.000.000-00', 'placeholder'=>'CPF', 'maxlength'=>14]) !!}
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-8">
                    <div class="form-input">
                        {!! Form::text('birthDate', '', ['id'=>'birthDate', 'class'=>'form-control input-main', 'data-mask'=>'00/00/0000', 'placeholder'=>'Data nascimento', 'maxlength'=>10]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group juridical-people hidden">
                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-10">
                    <div class="form-input">
                        {!! Form::text('cnpj', '', ['id'=>'cnpj', 'class'=>'form-control input-main', 'data-mask'=>'00.000.000/0000-00', 'placeholder'=>'CNPJ', 'maxlength'=>18]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
                    <div class="form-input">
                        {!! Form::text('phone', '', ['id'=>'phone', 'class'=>'form-control input-main', 'placeholder'=>'Telefone p/ contato', 'maxlength'=>14]) !!}
                    </div>
                </div>

                <div class="col-lg-4 col-md-5 col-sm-5 col-xs-10">
                    <div class="form-input">
                        {!! Form::text('mobile', '', ['id'=>'mobile', 'class'=>'form-control input-main', 'placeholder'=>'Celular p/ contato', 'maxlength'=>15]) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
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

        'birthDate': {
            dateBR: true,
            required: function(element){
                if($("#peopleType:checked").val() == 'Física'){
                    return true;
                }else{
                    return false;
                }
            }
        },
        'productReasonOfContact': {
            required: true
        },
        'peopleType': {
            required: true
        },
        'cpf': {
            cpf: true,
            required: function(element){
                if($("#peopleType:checked").val() == 'Física'){
                    return true;
                }else{
                    return false;
                }
            }
        },
        'notifierBirthDay': {
            dateBR: true,
            required: function(element){
                if($("#peopleType:checked").val() == 'Física'){
                    return true;
                }else{
                    return false;
                }
            }
        },
        'cnpj': {
            cnpj: true,
            required: function(element){
                if($("#peopleType:checked").val() == 'Jurídica'){
                    return true;
                }else{
                    return false;
                }
            }
        },
        'youAre': {
            required: function(element){
                if($("#peopleType:checked").val() == 'Física'){
                    return true;
                }else{
                    return false;
                }
            }
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
