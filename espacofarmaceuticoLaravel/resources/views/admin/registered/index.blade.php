@extends('admin.sidebar-template')

@section('title', 'Cadastrados | ')

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
                    Cadastrados <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li>Cadastrados</li>
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
                <h3 class="block-title">Lista</h3>
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
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>CRF</th>
                        <th>Cidade/UF</th>
                        <th>Status</th>
                        <th class="text-center sorting-none" style="width: 90px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="font-w600">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->crf }}</td>
                            <td>{{ $user->city."/".$user->state }}</td>
                            <td>
                                @if($user->active == 0)
                                <span class="label label-warning">Inativo</span>
                                @else
                                <span class="label label-success">Ativo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {!! Form::open([
                                    'id' => 'textStatus'.$user->id,
                                    'method' => 'put',
                                    'enctype' => 'multipart/form-data',
                                    'url' => route('registeredStatus')
                                    ])
                                !!}
                                    {!! Form::hidden('userId', $user->id) !!}
                                    @if($user->active == 0)
                                    {!! Form::hidden('active', 1) !!}
                                    {!! Form::button('<i class="fa fa-check"></i>', ['title'=>'Liberar', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-xs btn-success']) !!}
                                    @else
                                    {!! Form::hidden('active', 0) !!}
                                    {!! Form::button('<i class="fa fa-ban"></i>', ['title'=>'Bloquear', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-xs btn-warning']) !!}
                                    @endif
                                {!! Form::close() !!}

                                {!! Form::button('<i class="fa fa-search"></i> ', ['title'=>'Visualizar', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-info',
                                    'onclick'=>'window.open(\''.route('registeredView', $user->id).'\', \'_self\')']) !!}

                                {!! Form::open([
                                    'id' => 'textDelete'.$user->id,
                                    'method' => 'delete',
                                    'enctype' => 'multipart/form-data',
                                    'url' => ''
                                    ])
                                !!}
                                {!! Form::hidden('userId', $user->id) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                'data-url'=>route('registeredDelete'), 'data-form'=>true, 'data-id-form'=>'textDelete'.$user->id]) !!}
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
<!-- Page JS Code -->
<script src="{{ asset('assets/admin/js/pages/base_tables_datatables.js') }}"></script>
<!-- Personalizing dataTable -->
<script>
    jQuery(function(){
        jQuery('.js-dataTable-full').dataTable({
            order: [[0, 'desc']],
            columnDefs: [ { orderable: false, targets: 'sorting-none' } ],
            pageLength: 10,
            lengthMenu: [[10, 20, 50, 100], [10, 20, 50, 100]],
            language: {
                'url': '<?php echo asset('assets/json/dataTablesPT-BR.json'); ?>'
            }
        });
    });
</script>
@stop