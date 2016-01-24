@extends('admin.sidebar-template')

@section('title', 'Editar Evento | ')

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Eventos <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('events') }}" class="text-orange" title="Eventos">Eventos</a></li>
                    <li>{{ $event->title }}</li>
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
                        <button type="button" class="btn-back" data-url="{{ route('events') }}"><i class="si si-action-undo"></i></button>
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
                        'id' => 'events',
                        'method' => 'put',
                        'class' => 'form-horizontal push-20-t',
                        'enctype' => 'multipart/form-data',
                        'url' => route('eventsEditPut')
                        ])
                    !!}
                    {!! Form::hidden('eventsId', $event->eventsId) !!}
                    <div class="form-group">
                        <div class="col-lg-2 col-md-4 col-sm-8 col-xs-10">
                            <div class="form-input">
                                {!! Form::label('type', 'Tipo *') !!}
                                <select name="type" id="type" class="form-control">
                                    <option value="">Escolha...</option>
                                    <option value="0" @if($event->type == 0){{ 'selected' }}@endif>Nacional</option>
                                    <option value="1" @if($event->type == 1){{ 'selected' }}@endif>Internacional</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('date', 'Data *') !!}
                                {!! Form::text('date', $event->date, ['class'=>'js-datepicker js-masked-date form-control', 'data-date-format' => 'dd/mm/yyyy', 'placeholder' => 'dd/mm/yyyy', 'id'=>'date', 'maxlength'=>10]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('title', 'Título *') !!}
                                {!! Form::text('title', $event->title, ['class'=>'form-control', 'id'=>'title', 'maxlength'=>100]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('details', 'Detalhes *') !!}
                                {!! Form::textarea('details', $event->details, ['class'=>'form-control', 'id'=>'details']) !!}
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
<script src="{{ asset('assets/admin/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/masked-inputs/jquery.maskedinput.min.js') }}"></script>
<script src="{{ asset('assets/admin/editor/ckeditor/ckeditor.js') }}"></script>
<script type="application/javascript">
$(function(){
    //START CKEDITOR CODE
    CKEDITOR.replace('details');
    //START VALIDATE FORM CODE
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
            'type': {
                required: true
            },
            'date': {
                required: true
            },
            'title': {
                required: true
            },
            'details': {
                required: function()
                {
                    CKEDITOR.instances.details.updateElement();
                }
            }
        },
        messages: {
            'type': {
                required: 'Escolha o tipo do evento'
            },
            'date': {
                required: 'Informe a data do evento'
            },
            'title': {
                required: 'Informe o título do evento'
            },
            'details': {
                required: 'Informe os detalhes do evento'
            }
        }
    });
    // Init page helpers (BS Datepicker + Masked Input)
    App.initHelpers(['datepicker', 'masked-inputs']);
});
</script>
@stop
