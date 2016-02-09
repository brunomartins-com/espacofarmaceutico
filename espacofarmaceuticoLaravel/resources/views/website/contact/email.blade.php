<!DOCTYPE html>
<html lang="pt-br">
<head></head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
    <br />
    <strong>Nome: </strong> {{ $request->name }}
    <br />
    <strong>E-mail:</strong> {{ $request->email }}
    <br />
    @if($request->peopleType == 'Física')
        <strong>CPF: </strong> {{ $request->cpf }}
        <br />
        <strong>Data Nascimento: </strong> {{ $request->birthDate }}
        <br />
        <strong>Você é:</strong> {{ $request->youAre }}
        <br />
    @else
        <strong>CNPJ: </strong> {{ $request->cnpj }}
        <br />
    @endif
    <strong>CEP: </strong> {{ $request->zipCode }}
    <br />
    <strong>Endereço: </strong> {{ $request->address }}
    <br />
    <strong>Cidade / Estado:</strong> {{ $request->city }} - {{ $request->state }}
    <br />
    @if(!empty($request->phone))
        <strong>Tel fixo: </strong> {{ $request->phone }}
        <br />
    @endif
    @if(!empty($request->mobile))
        <strong>Celular: </strong> {{ $request->mobile }}
        <br />
    @endif
    <br />
    <strong>Mensagem: </strong> {!! nl2br(e($request->message)) !!}
    <br /><br />
    <img src="{{ url('assets/images/logo-espaco-farmaceutico.png') }}" alt="Espaço Farmacêutico - Teuto/Pfizer" />
    <br />
    <br />
    <br />
    <strong>IP do internauta: {{ $request->ip() }}</strong>
    <br />
    <strong>Dados enviados em: {{ $request->date }}</strong>
</body>
</html>