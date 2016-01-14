@extends('admin.sidebar-template')

@section('title', 'Fotos | ')

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
                    Fotos <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li>Fotos</li>
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
                <div class="alert alert-info alert-dismissable">
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
                        <th style="width: 200px;">Enviada em</th>
                        <th width="180">Foto</th>
                        <th>Participante</th>
                        <th width="150">Status</th>
                        <th class="text-center sorting-none" style="width: 100px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($photos as $photo)
                        <tr>
                            <td>{{ $photo->created_at->format('d/m/Y H:i') }}</td>
                            <td><img src="{{ "/".$folder.$photo->userId."/thumb_".$photo->photo }}" height="100" alt="{{ $photo->babyName }}" /></td>
                            <td>
                                <span class="font-w600">
                                    {{ $photo->babyName }}
                                    <br />
                                    {{ $photo->name }}
                                </span>
                            </td>
                            <td class="font-w600">
                                @if($photo->status == 0)
                                <span class="label label-warning">Inativo</span>
                                @elseif($photo->status == 1)
                                <span class="label label-success">Ativo</span>
                                @elseif($photo->status == 2)
                                <span class="label label-primary">Finalista</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($photo->status == 0)
                                    {!! Form::open([
                                        'id' => 'textStatus'.$photo->photosId,
                                        'method' => 'put',
                                        'enctype' => 'multipart/form-data',
                                        'url' => route('photosStatus')
                                        ])
                                    !!}
                                        {!! Form::hidden('photosId', $photo->photosId) !!}
                                        {!! Form::hidden('status', 1) !!}
                                        {!! Form::button('<i class="fa fa-check"></i>', ['title'=>'Liberar', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-xs btn-success']) !!}
                                    {!! Form::close() !!}
                                @endif

                                @if($photo->status > 0)
                                    {!! Form::open([
                                        'id' => 'textStatus'.$photo->photosId,
                                        'method' => 'put',
                                        'enctype' => 'multipart/form-data',
                                        'url' => route('photosStatus')
                                        ])
                                    !!}
                                        {!! Form::hidden('photosId', $photo->photosId) !!}
                                        {!! Form::hidden('active', 0) !!}
                                        {!! Form::button('<i class="fa fa-ban"></i>', ['title'=>'Bloquear', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-xs btn-warning']) !!}
                                    {!! Form::close() !!}
                                @endif

                                {!! Form::open([
                                    'id' => 'textDelete'.$photo->photosId,
                                    'method' => 'delete',
                                    'enctype' => 'multipart/form-data',
                                    'url' => ''
                                    ])
                                !!}
                                {!! Form::hidden('photosId', $photo->photosId) !!}
                                {!! Form::hidden('usersId', $photo->userId) !!}
                                {!! Form::hidden('photo', $photo->photo) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                'data-url'=>route('photosDelete'), 'data-form'=>true, 'data-id-form'=>'textDelete'.$photo->photosId]) !!}
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
            lengthMenu: [[25, 50, 100, 200], [25, 50, 100, 200]],
            language: {
                'url': '<?php echo asset('assets/json/dataTablesPT-BR.json'); ?>'
            }
        });
    });
</script>
@stop