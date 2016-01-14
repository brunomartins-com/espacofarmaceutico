@extends('template.admin')

@section('content')
<div class="content overflow-hidden">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <div class="block block-themed animated fadeIn">
                <div class="block-header bg-primary">
                    <ul class="block-options">
                        <li>
                            <a href="{!! route('login') !!}"  title="Entrar"><i class="si si-login"></i> Entrar</a>
                        </li>
                    </ul>
                    <h3 class="block-title">Recuperar Senha</h3>
                </div>
                <div class="block-content block-content-full block-content-narrow">
                    <img src="{{ asset('assets/admin/img/logoLogin.png') }}" alt="Concurso Bebê Hipoderme - 12ª Edição" class="center-block" width="80%" />

                    @if (session('status'))
                        <div class="alert alert-primary">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Houve alguns problemas com a os dados.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="js-validation-reminder form-horizontal push-50-t push-50" action="{{ route('passwordEmail') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material form-material-primary">
                                    <input class="form-control" type="text" placeholder="Informe seu e-mail" id="email" value="{{ old('email') }}" name="email">
                                    <label for="email">E-mail</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-6 col-md-5">
                                <button class="btn btn-block btn-primary" type="submit"><i class="si si-envelope-open"></i> Enviar E-mail</button>
                            </div>
                        </div>
                    </form><!-- END Forgot Form -->
                </div>
            </div><!-- END Forgot Block -->
        </div>
    </div>
</div>
@stop

@section('javascript')
<script type="application/javascript">
$(function(){
    $('.js-validation-reminder').validate({
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
            }
        },
        messages: {
            'email': {
                required: 'Por favor informe seu e-mail'
            }
        }
    });
});
</script>
@stop