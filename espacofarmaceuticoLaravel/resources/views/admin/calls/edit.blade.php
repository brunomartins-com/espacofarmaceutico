@extends('admin.sidebar-template')

@section('title', 'Editar Chamada | ')

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Chamadas <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('calls') }}" class="text-primary" title="Chamadas">Chamadas</a></li>
                    <li>{{ $call->title }}</li>
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
            <div class="block-header bg-primary-darker text-white">
                <ul class="block-options">
                    <li>
                        <button type="button" class="btn-back" data-url="{{ route('calls') }}"><i class="si si-action-undo"></i></button>
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
                        'id' => 'calls',
                        'method' => 'put',
                        'class' => 'form-horizontal push-20-t',
                        'enctype' => 'multipart/form-data',
                        'url' => route('callsEditPut')
                        ])
                    !!}
                    {!! Form::hidden('callsId', $call->callsId) !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('title', 'Título *') !!}
                                {!! Form::text('title', $call->title, ['class'=>'form-control', 'id'=>'title', 'maxlength'=>45]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('text', 'Texto *') !!}
                                {!! Form::textarea('text', $call->text, ['class'=>'form-control', 'id'=>'text', 'rows'=>4]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('warning', 'Aviso') !!}
                                {!! Form::text('warning', $call->warning, ['class'=>'form-control', 'id'=>'warning', 'maxlength'=>40]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('url', 'Link') !!}
                                {!! Form::text('url', $call->url, ['class'=>'form-control', 'id'=>'url', 'maxlength'=>255]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-2 col-md-4 col-sm-8 col-xs-10">
                            <div class="form-input">
                                {!! Form::label('target', 'Como Abrir') !!}
                                <select name="target" id="target" class="form-control">
                                    <option value="">Escolha...</option>
                                    <option value="_self" @if($call->target == '_self'){{ 'selected' }}@endif>Mesma Janela</option>
                                    <option value="_blank" @if($call->target == '_blank'){{ 'selected' }}@endif>Nova Janela</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('currentImage', 'Imagem') !!}
                            <div class="clearfix"></div>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new" style="max-width:{{ $imageDetails['imageWidth'] }}px; max-height:{{ $imageDetails['imageHeight'] }}px;">
                                    <img src="{!! url($imageDetails['folder'].$call->image)."?".time() !!}" alt="{{ $call->title }}" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width:{{ $imageDetails['imageWidth'] }}px; max-height:{{ $imageDetails['imageHeight'] }}px; border-radius: 0; border:0; padding: 0;"></div>
                                <div class="font-size-10 push-10-t">Tamanho: {{ $imageDetails['imageWidth']." x ".$imageDetails['imageHeight'] }} pixels / Somente .png com transparência</div>
                                <div class="push-20-t">
                                    <span class="btn btn-primary btn-xs btn-file">
                                        <span class="fileupload-new">Selecione a Imagem</span>
                                        <span class="fileupload-exists">Trocar</span>
                                        {!! Form::hidden('currentImage', $call->image) !!}
                                        {!! Form::file('image', ['id'=>'image', 'accept'=>'image/png']) !!}
                                    </span>
                                    <a href="#" class="btn btn-primary btn-xs fileupload-exists" data-dismiss="fileupload" style="margin-left: 30px;">Cancelar</a>
                                </div>
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
            'title': {
                required: true
            },
            'text': {
                required: true
            }
        },
        messages: {
            'title': {
                required: 'Informe o título da chamada'
            },
            'text': {
                required: 'Informe o texto da chamada'
            }
        }
    });
});
</script>
@stop
