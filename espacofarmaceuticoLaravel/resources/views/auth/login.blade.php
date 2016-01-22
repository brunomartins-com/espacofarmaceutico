@extends('template.admin')

@section('content')
<div class="content overflow-hidden">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <!-- Login Block -->
            <div class="block block-themed animated fadeIn">
                <div class="block-header bg-orange">
                    <ul class="block-options">
                        <li>
                            <a href="{!! route('passwordEmail') !!}" title="Esqueceu sua senha?"><i class="si si-wrench"></i> Esqueceu sua senha?</a>
                        </li>
                    </ul>
                    <h3 class="block-title">Entrar</h3>
                </div>
                <div class="block-content block-content-full block-content-narrow">
                    <img src="{{ asset('assets/admin/img/logoLogin.png') }}" alt="Concurso Bebê Hipoderme - 12ª Edição" class="center-block" width="80%" />

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Ops!</strong> Houve alguns problemas com a os dados.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="js-validation-login form-horizontal push-50-t push-50" action="{!! route('login') !!}" method="post">
                        {!! Form::hidden('type', 0) !!}
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-warning">
                                    <input class="form-control" type="text" placeholder="Informe seu e-mail" id="email" value="{{ old('email') }}" name="email">
                                    <label for="email">E-mail</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-warning">
                                    <input class="form-control" type="password" placeholder="Informe sua senha" id="password" name="password">
                                    <label for="password">Senha</label>
                                </div>
                            </div>
                        </div>

                         <div class="form-group">
                             <div class="col-xs-12">
                                 <label class="css-input switch switch-sm switch-warning">
                                     <input type="checkbox" id="remember" name="remember"><span></span> Lembrar Dados?
                                 </label>
                             </div>
                         </div>

                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <button class="btn btn-block btn-warning" type="submit"><i class="si si-login"></i>Entrar</button>
                            </div>
                        </div>
                    </form><!-- END Login Form -->
                </div>
            </div><!-- END Login Block -->
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="application/javascript">
$(function(){
    $('.js-validation-login').validate({
        errorClass: 'help-block text-right animated fadeInDown',
        errorElement: 'div',
        errorPlacement: function(error, e) {
            jQuery(e).parents('.form-group .form-material').append(error);
        },
        highlight: function(e) {
            jQuery(e).closest('.form-group').removeClass('has-error').addClass('has-error');
            jQuery(e).closest('.help-block').remove();
        },
        success: function(e) {
            jQuery(e).closest('.form-group').removeClass('has-error');
            jQuery(e).closest('.help-block').remove();
        },
        rules: {
            'email': {
                required: true,
                email: true
            },
            'password': {
                required: true
            }
        },
        messages: {
            'email': {
                required: 'Por favor informe seu e-mail'
            },
            'password': {
                required: 'Por favor informe sua senha'
            }
        }
    });
});
</script>
@stop