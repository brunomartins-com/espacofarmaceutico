<!DOCTYPE html>
<html lang="pt-br">
<head></head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px;">
    <br />
    <strong>Nome do Paciente: </strong> {{ $request->patientName }}
    <br />
    <strong>Peso/Altura: </strong> {{ $request->weight." Kg / ".$request->height." m" }}
    <br />
    <strong>Data Nascimento: </strong> {{ $request->birthDate }}
    <br />
    <strong>Sexo do Paciente: </strong> {{ $request->gender }}
    <br />
    <strong>Descrição da reação: </strong> {{ nl2br(e($request->reactionDescription)) }}
    <br />
    <strong>Produto que causou a reação: </strong> {{ $request->productReaction }}
    <br />
    <strong>Motivo do uso do produto: </strong> {{ nl2br(e($request->productReasonOfUse)) }}
    <br />
    <strong>Lote do produto: </strong> {{ $request->productBatch }}
    <br />
    <strong>Nome do notificador: </strong> {{ $request->notifierName }}
    <br />
    @if($request->peopleType == 'Física')
        <strong>CPF: </strong> {{ $request->cpf }}
        <br />
        <strong>Data Nascimento: </strong> {{ $request->notifierBirthDate }}
        <br />
    @else
        <strong>CNPJ: </strong> {{ $request->cnpj }}
        <br />
    @endif
    <strong>Você é:</strong> {{ $request->youAre }}
    @if(!empty($request->phone))
        <br />
        <strong>Tel fixo: </strong> {{ $request->phone }}
    @endif
    @if(!empty($request->mobile))
        <br />
        <strong>Celular: </strong> {{ $request->mobile }}
    @endif
    <br />
    <strong>E-mail:</strong> {{ $request->email }}
    <br />
    <strong>Informações Adicionais: </strong> {!! nl2br(e($request->additionalInformations)) !!}
    <br />
    <strong>Aceitou receber Newsletter?:</strong> {{ $request->newsletter }}
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