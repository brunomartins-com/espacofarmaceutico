@extends('admin.sidebar-template')

@section('title', 'Editar (Legislação) | ')

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Legislação <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('legislation') }}" class="text-orange" title="Legislação">Legislação</a></li>
                    <li>{{ $legislation->title }}</li>
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
                        <button type="button" class="btn-back" data-url="{{ route('legislation') }}"><i class="si si-action-undo"></i></button>
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
                        'id' => 'legislation',
                        'method' => 'put',
                        'class' => 'form-horizontal push-20-t',
                        'enctype' => 'multipart/form-data',
                        'url' => route('legislationEditPut')
                        ])
                    !!}
                    {!! Form::hidden('legislationId', $legislation->legislationId) !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('legislationCategoriesId', 'Categoria *', ['style' => 'width:100%; float:left; clear:both;']) !!}
                                <div class="boxSelectLegislationCategoriesId">{!! Form::select('legislationCategoriesId', $categories, $legislation->legislationCategoriesId, ['id' => 'legislationCategoriesId', 'class' => 'form-control', 'style' => 'width:80%; float:left;']) !!}</div>
                                {!! Form::button('<i class="fa fa-wrench"></i>', ['id' => 'btnSelectLegislationCategoriesId', 'class' => 'btn btn-xs btn-inverse', 'style' => 'margin:5px 0 0 10px;',
                                                 'data-toggle' => 'modal', 'href' => '#modalSelectLegislationCategoriesId']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('title', 'Título *') !!}
                                {!! Form::text('title', $legislation->title, ['class'=>'form-control', 'id'=>'title', 'maxlength'=>45]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('text', 'Texto *') !!}
                                {!! Form::textarea('text', $legislation->text, ['class'=>'form-control', 'id'=>'text']) !!}
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
<div id="modalSelectLegislationCategoriesId" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
@stop

@section('javascript')
@parent
<script src="{{ asset('assets/admin/editor/ckeditor/ckeditor.js') }}"></script>
<script type="application/javascript">
$(function(){
    //START CKEDITOR CODE
    CKEDITOR.replace('text');
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
            'legislationCategoriesId': {
                required: true
            },
            'title': {
                required: true
            },
            'text': {
                required: function()
                {
                    CKEDITOR.instances.text.updateElement();
                }
            }
        },
        messages: {
            'legislationCategoriesId': {
                required: 'Por favor escolha a categoria'
            },
            'title': {
                required: 'Por favor informe o título'
            },
            'text': {
                required: 'Por favor informe o texto'
            }
        }
    });
    //OPEN MODAL FOR ADD CATEGORIES
    $('#btnSelectLegislationCategoriesId').click(function(){
        var modal = $(this).attr('href');
        $(modal).load('{{ route('selectCategory', ['Categorias', 'SelectLegislationCategoriesId', 'legislationCategories']) }}',function(result){
            $(modal).modal({show:true});
        });
    });
});
</script>
@stop
