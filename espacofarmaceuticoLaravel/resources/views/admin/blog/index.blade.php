@extends('admin.sidebar-template')

@section('title', 'Blog | ')

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
                    Blog <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li>Blog</li>
                    <li>Lista</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- END Page Header -->

    <!-- Page Content -->
    <div class="content">
        <!-- Dynamic Table Full -->
        <div class="block">
            <div class="block-header bg-gray-lighter">
                <ul class="block-options">
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">
                    {!! Form::button('<i class="fa fa-plus"></i> Adicionar', ['class'=>'btn btn-primary', 'onclick'=>'window.open(\''.route('blogAdd').'\', \'_self\');']) !!}
                </h3>
            </div>
            <div class="block-content">
                @if (Session::has('success'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! Session::get('success') !!}
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
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                <table class="table table-bordered table-striped js-dataTable-full">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Id</th>
                            <th width="150">Data</th>
                            <th>Título</th>
                            <th width="200">Lida</th>
                            <th class="text-center sorting-none" style="width: 70px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($blog as $item)
                        <tr>
                            <td>{{ $item->blogId }}</td>
                            <td>{{ $item->date }}</td>
                            <td class="font-w600">{{ $item->title }}</td>
                            <td>{{ $item->read }} {{ 'vez' }}@if($item->read > 1){{ 'es' }}@endif</td>
                            <td class="text-center">
                                {!! Form::button('<i class="fa fa-pencil"></i>', ['title'=>'Editar', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-primary',
                                'onclick'=>'window.open(\''.route('blogEdit', $item->blogId).'\', \'_self\')']) !!}

                                {!! Form::open([
                                    'id' => 'textDelete'.$item->blogId,
                                    'method' => 'delete',
                                    'enctype' => 'multipart/form-data',
                                    'url' => ''
                                    ])
                                !!}
                                {!! Form::hidden('blogId', $item->blogId) !!}
                                {!! Form::hidden('image', $item->image) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                'data-url'=>route('blogDelete'), 'data-form'=>true, 'data-id-form'=>'textDelete'.$item->blogId]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
<script src="{{ asset('assets/admin/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/plugins/datatables/jquery.dataUk.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/admin/js/pages/base_tables_datatables.js') }}"></script>
<!-- Personalizing dataTable -->
<script>
jQuery(function(){
    jQuery('.js-dataTable-full').dataTable({
        order: [[1, 'desc']],
        "aoColumnDefs" : [
            { "sType": 'date-uk', "aTargets": [1] },
            { orderable: false, targets: 'sorting-none' }
        ],
        pageLength: 20,
        lengthMenu: [[20, 50, 100, 200], [20, 50, 100, 200]],
        language: {
            'url': '<?php echo asset('assets/json/dataTablesPT-BR.json'); ?>'
        }
    });
});
</script>
@stop
