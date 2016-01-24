@extends('admin.sidebar-template')

@section('title', 'Ordenar Vagas (Trabalhe Conosco) | ')

@section('head')
@parent
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{ asset('assets/admin/js/plugins/datatables/jquery.dataTables.min.css') }}">
@stop

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Trabalhe Conosco - Vagas <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('workWithUsVacancies') }}" class="text-orange" title="Trabalhe Conosco - Vagas">Trabalhe Conosco - Vagas</a></li>
                    <li>Ordenar</li>
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
                        <button type="button" class="btn-back" data-url="{{ route('workWithUsVacancies') }}"><i class="si si-action-undo"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">Ordenar</h3>
            </div>
            <div class="block-content">
                <div class="block-content block-content-full">
                    <div class="alert alert-warning alert-dismissable hidden">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <span></span>
                    </div>
                    <!-- For more info and examples you can check out https://jqueryui.com/sortable/ -->
                    <div class="row js-draggable-items">
                        <div class="col-sm-12 draggable-column">
                            {!! Form::open([
                                'id' => 'formOrder',
                                'class' => 'form-horizontal'
                                ])
                            !!}
                            {!! Form::hidden('databaseTable', 'workWithUsVacancies') !!}
                            {!! Form::hidden('primaryKey', 'workWithUsVacanciesId') !!}
                            {!! Form::hidden('sortorder', '') !!}
                            @foreach($items as $item)
                            <!-- Block -->
                            <div class="block draggable-item" title="{{ $item->workWithUsVacanciesId }}">
                                <div class="block-header">
                                    <ul class="block-options">
                                        <li>
                                            <span class="draggable-handler text-gray"><i class="si si-cursor-move"></i></span>
                                        </li>
                                    </ul>
                                    <h3 class="block-title">{{ $item->title }}</h3>
                                </div>
                            </div>
                            <!-- END Block -->
                            @endforeach
                            <div class="form-group">
                                <div class="col-xs-12 push-30-t">
                                    {!! Form::button('Concluído', ['class'=>'btn btn-primary btn-back pull-left', 'data-url'=>route('workWithUsVacancies')]) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <!-- END Draggable Items with jQueryUI -->
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
<!-- Page JS Plugins -->
<script src="{{ asset('assets/admin/js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/sortorder.js') }}"></script>
<script>
$(function () {
    // Init page helpers (jQueryUI)
    App.initHelpers('draggable-items');
});
</script>
@stop
