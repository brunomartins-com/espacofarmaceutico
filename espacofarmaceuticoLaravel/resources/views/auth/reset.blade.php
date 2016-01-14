@extends('template.admin')

@section('content')
	<div class="content overflow-hidden">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
				<!-- Register Block -->
				<div class="block block-themed animated fadeIn">
					<div class="block-header bg-primary">
						<h3 class="block-title">Recuperação de Senha</h3>
					</div>
					<div class="block-content block-content-full block-content-narrow">
						<img src="{{ asset('assets/admin/img/logoLogin.png') }}" alt="Concurso Bebê Hipoderme - 12ª Edição" class="center-block" width="80%" />

						@if (Session::has('status'))
						<div class="alert alert-primary alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							{!! Session::get('status') !!}
						</div>
						@endif
						@if (count($errors) > 0)
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							@foreach ($errors->all() as $error)
								<p>{{ $error }}</p>
							@endforeach
						</div>
						@endif

						<form class="js-validation-recovery form-horizontal push-50-t push-50" action="{!! route('passwordReset') !!}" method="post">
							{!! csrf_field() !!}
							<input type="hidden" name="token" value="{{ $token }}" >

							<div class="form-group">
								<div class="col-xs-12">
									<div class="form-material form-material-primary">
										<input class="form-control" type="email"  id="email" name="email"  value="{{ old('email') }}" placeholder="Informe seu e-mail">
										<label for='email'>E-mail</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-xs-12">
									<div class="form-material form-material-primary">
										<input class="form-control" type="password" id="password" name="password" placeholder="Digite a nova senha...">
										<label for='password'>Senha</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-xs-12">
									<div class="form-material form-material-primary">
										<input class="form-control" type="password" id="password_confirmation" name="password_confirmation" placeholder="..e confirme-a">
										<label for="password_confirmation">Re-digite a Senha</label>
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-xs-12 col-sm-6 col-md-6">
									<button class="btn btn-block btn-primary" type="submit"><i class="fa fa-save"></i> Salvar</button>
								</div>
							</div>
						</form><!-- END Recovery Form -->
					</div>
				</div><!-- END Recovery Block -->
			</div>
		</div>
	</div>
@stop

@section('javascript')
<script type="application/javascript">
	$(function(){
		$('.js-validation-recovery').validate({
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
				},
				'password_confirmation': {
					required: true,
					equalTo: '#password'
				}
			},
			messages: {
				'email': {
					required: 'Por favor informe seu e-mail'
				},
				'password': {
					required: 'Por favor informe sua senha'
				},
				'password_confirmation': {
					required: 'Por favor confirme sua senha',
					equalTo: 'As senhas devem ser iguais'
				}
			}
		});
	});
</script>
@stop