@extends('admin.sidebar-template')

@section('title', 'Editar Usuário | ')

@section('page-content')
@parent
        <!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Usuários <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('users') }}" class="text-orange" title="Usuários">Usuários</a></li>
                    <li>{{ $user->name }}</li>
                    <li>Editar</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END Page Header -->

    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Full -->
        <div class="block">
            <div class="block-header bg-gray-darker text-white">
                <ul class="block-options">
                    <li>
                        <button type="button" class="btn-back" data-url="{{ route('users') }}"><i class="si si-action-undo"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">Editar</h3>
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
                            'id' => 'users',
                            'method' => 'put',
                            'class' => 'form-horizontal push-20-t',
                            'enctype' => 'multipart/form-data',
                            'url' => route('usersEditPut')
                            ])
                    !!}
                    {!! Form::hidden('userId', $user->id) !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('name', 'Nome *') !!}
                                {!! Form::text('name', $user->name, ['class'=>'form-control', 'id'=>'name', 'maxlength'=>50]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('email', 'E-mail *') !!}
                                {!! Form::text('email', $user->email, ['class'=>'form-control', 'id'=>'email', 'maxlength'=>50]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('password', 'Nova Senha') !!}
                                <input name="password" id="password" type="password" class="form-control" maxlength="12" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('password_confirmation', 'Confirma Senha') !!}
                                <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" maxlength="12" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12 push-30-t">
                            {!! Form::submit('Gravar', ['class'=>'btn btn-primary pull-left']) !!}
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
