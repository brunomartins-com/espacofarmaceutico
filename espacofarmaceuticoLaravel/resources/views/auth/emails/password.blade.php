<!DOCTYPE html>
<html lang="pt-br">
<body style="background-color:#F5F5F5">
<div style="width: 100%; float: left; clear: both; margin: auto">
    <div style="margin: 0 auto;">
        <div style="width:50%; height: 100%; min-height: 100%; margin-top: 40px; display: block; background-color: #fff;">
            <div style="width: 100%;">
                <img src="{{ url('assets/images/logo-espaco-farmaceutico.png') }}" alt="Espaço Farmacêutico - Teuto/Pfizer" />
            </div>
            <h4 style="font-size: 18px; font-weight: normal;">Olá <strong>{{ $user->name  }}</strong></h4>
            <p>Este e-mail é para a recuperação de senha, para completar sua solicitação
                @if($user->type == 0)
                <a href="{{ url('admin/recuperar-senha/'.$token) }}" target="_blank">clique aqui</a>
                @else
                <a href="{{ url('recuperar-senha/'.$token) }}" target="_blank">clique aqui</a>
                @endif
            </p>
            <br />
            <br />
            <br />
            <strong>Dados enviados em: {{ date('d/m/Y H:i:s') }}</strong>
        </div>
    </div><!-- end .row -->
</div><!-- end .containter -->
</body>
</html>