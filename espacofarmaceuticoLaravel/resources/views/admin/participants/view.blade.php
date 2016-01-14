@extends('admin.sidebar-template')

@section('title', 'Visualizar Participante | ')

@section('page-content')
@parent
        <!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Participantes <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('participants') }}" title="Participantes">Participantes</a></li>
                    <li>{{ $user->babyName }}</li>
                    <li>Visualizar</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END Page Header -->

    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Full -->
        <div class="block">
            <div class="block-header bg-primary-darker text-white">
                <ul class="block-options">
                    <li>
                        <button type="button" class="btn-back" data-url="{{ route('participants') }}"><i class="si si-action-undo"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">Visualizar</h3>
            </div>
            <div class="block-content">
                @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                <!-- .block-content -->
                <div class="block-content block-content-full">
                    {!! Form::open([
                        'id' => 'textStatus'.$user->id,
                        'method' => 'put',
                        'class' => 'form-horizontal push-20-t',
                        'enctype' => 'multipart/form-data',
                        'url' => route('participantsStatus')
                        ])
                    !!}
                    {!! Form::hidden('userId', $user->id) !!}
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Bebê: ') !!}
                                {{ $user->babyName }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Data de Nascimento: ') !!}
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $user->babyBirthdate)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Sexo do Bebê: ') !!}
                                {{ $user->babyGender }}
                            </div>
                        </div>
                    </div>
                    @if(!empty($user->birthCertificate))
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Certidão de Nascimento: ') !!}
                                <a href="{{ asset('assets/images/_upload/participantes/'.$user->birthCertificate) }}" target="_blank" class="btn btn-xs btn-info"><em class="fa fa-clipboard"></em> Ver</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Responsável: ') !!}
                                {{ $user->name }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Sexo do Responsável: ') !!}
                                {{ $user->gender }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'RG: ') !!}
                                {{ $user->rg }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'CPF: ') !!}
                                {{ $user->cpf }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Endereço: ') !!}
                                {{ $user->address.", nº ".$user->number }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Bairro: ') !!}
                                {{ $user->district }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Complemento: ') !!}
                                {{ $user->complement }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Cidade/UF: ') !!}
                                {{ $user->city."/".$user->state }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Tel. fixo: ') !!}
                                {{ $user->phone }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Celular: ') !!}
                                {{ $user->mobile }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'E-mail: ') !!}
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                    @if(count($photos) > 0)
                    <br /><br />
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                <h3>FOTOS</h3>
                                <ul>
                                    @foreach($photos as $photo)
                                    <li>
                                        <a href="{{ asset('assets/images/_upload/fotos/'.$user->id.'/'.$photo->photo) }}" target="_blank" title="Ampliar">
                                            <img src="{{ asset('assets/images/_upload/fotos/'.$user->id.'/thumb_'.$photo->photo) }}" alt="{{ $user->babyName }}" />
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(count($videos) > 0)
                    <br /><br />
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                <h3>VÍDEO</h3>
                                <ul>
                                    @foreach($videos as $video)
                                    <li>
                                        <a href="{{ $video->url }}" target="_blank" title="Ampliar">
                                            <img src="{{ $video->image }}" alt="{{ $user->babyName }}" />
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(count($usersReceipts) > 0)
                    <br /><br />
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                <h3>CUPONS</h3>
                                <ul>
                                    @foreach($usersReceipts as $userReceipt)
                                    <li>
                                        <a href="{{ asset('assets/images/_upload/participantes/'.$userReceipt->receipt) }}" target="_blank" title="Ampliar">
                                            <img src="{{ asset('assets/images/_upload/participantes/thumb_'.$userReceipt->receipt) }}" alt="{{ $user->babyName }}" />
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="col-xs-12 push-30-t">
                            @if($user->active == 0)
                            {!! Form::hidden('active', 1) !!}
                            {!! Form::button('<i class="fa fa-check"></i> Liberar Participante', ['title'=>'Liberar', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-success']) !!}
                            @else
                            {!! Form::hidden('active', 0) !!}
                            {!! Form::button('<i class="fa fa-ban"></i> Bloquear Participante', ['title'=>'Bloquear', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-warning']) !!}
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- END Dynamic Table Full -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
@stop

@section('javascript')
@parent
<script type="application/javascript">
    $(function(){
        $('.form-horizontal').validate({
            errorClass: 'help-block text-right animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group .form-input').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error').addClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            ignore: [],
            rules: {
                'name': {
                    required: true
                },
                'email': {
                    required: true,
                    email: true
                },
                'password': {
                    minlength: 6,
                    maxlength: 12
                },
                'password_confirmation': {
                    required: function(element){
                        if($("#password").val() != ''){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    minlength: 6,
                    maxlength: 12,
                    equalTo:"#password"
                }
            },
            messages: {
                'name': {
                    required: 'Informe o nome do usuário'
                },
                'email': {
                    required: 'Informe o e-mail do usuário',
                    email: 'Informe um e-mail válido'
                },
                'password': {
                    minlength	: "A senha deve conter de 6 a 12 caracteres",
                    maxlength	: "A senha deve conter de 6 a 12 caracteres"
                },
                'password_confirmation': {
                    required	: "Confirme a senha",
                    minlength	: "A senha deve conter de 6 a 12 caracteres",
                    maxlength	: "A senha deve conter de 6 a 12 caracteres",
                    equalTo		: "As senhas não conferem"
                }
            }
        });
    });
</script>
@stop
