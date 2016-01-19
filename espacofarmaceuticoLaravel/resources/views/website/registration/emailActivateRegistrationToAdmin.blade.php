<!DOCTYPE html>
<html lang="pt-br">
<head></head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
    <br />
    O internauta <strong>{{ $request->name }}</strong>, email ({{ $request->email }}) efetuou um pré-cadastro no website www.espacofarmaceutico.com.br
    <br />
    <br />
    Maiores informações, acesse o painel e confira!
    <br />
    <br />
    <img src="{{ url('assets/images/logo-espaco-farmaceutico.png') }}" alt="Espaço Farmacêutico - Teuto/Pfizer" />
    <br />
    <br />
    <br />
    <strong>IP do internauta: {{ $request->ip() }}</strong>
    <br />
    <strong>Dados enviados em: {{ $request->date }}</strong>
</body>
</html>