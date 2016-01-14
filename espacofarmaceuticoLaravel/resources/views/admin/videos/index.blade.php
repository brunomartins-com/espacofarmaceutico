@extends('admin.sidebar-template')

@section('title', 'Vídeos | ')

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
                    Vídeos <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li>Vídeos</li>
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
                        <th style="width: 200px;">Enviado em</th>
                        <th width="180">Vídeo</th>
                        <th>Participante</th>
                        <th width="150">Status</th>
                        <th class="text-center sorting-none" style="width: 100px;">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($videos as $video)
                        <tr>
                            <td>{{ $video->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ $video->url }}" target="_blank" title="Visualizar Vídeo">
                                    <img src="{{ $video->image }}" height="100" alt="{{ $video->babyName }}" />
                                </a>
                            </td>
                            <td>
                                <span class="font-w600">
                                    {{ $video->babyName }}
                                    <br />
                                    {{ $video->name }}
                                </span>
                            </td>
                            <td class="font-w600">
                                @if($video->status == 0)
                                <span class="label label-warning">Inativo</span>
                                @elseif($video->status == 1)
                                <span class="label label-success">Ativo</span>
                                @elseif($video->status == 2)
                                <span class="label label-primary">Finalista</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($video->status == 0)
                                    {!! Form::open([
                                        'id' => 'textStatus'.$video->videosId,
                                        'method' => 'put',
                                        'enctype' => 'multipart/form-data',
                                        'url' => route('videosStatus')
                                        ])
                                    !!}
                                        {!! Form::hidden('videosId', $video->videosId) !!}
                                        {!! Form::hidden('status', 1) !!}
                                        {!! Form::button('<i class="fa fa-check"></i>', ['title'=>'Liberar', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-xs btn-success']) !!}
                                    {!! Form::close() !!}
                                @endif

                                @if($video->status > 0)
                                    {!! Form::open([
                                        'id' => 'textStatus'.$video->videosId,
                                        'method' => 'put',
                                        'enctype' => 'multipart/form-data',
                                        'url' => route('videosStatus')
                                        ])
                                    !!}
                                        {!! Form::hidden('videosId', $video->videosId) !!}
                                        {!! Form::hidden('active', 0) !!}
                                        {!! Form::button('<i class="fa fa-ban"></i>', ['title'=>'Bloquear', 'data-toggle'=>'tooltip', 'type' => 'submit', 'class'=>'btn btn-xs btn-warning']) !!}
                                    {!! Form::close() !!}
                                @endif

                                {!! Form::open([
                                    'id' => 'textDelete'.$video->videosId,
                                    'method' => 'delete',
                                    'enctype' => 'multipart/form-data',
                                    'url' => ''
                                    ])
                                !!}
                                {!! Form::hidden('videosId', $video->videosId) !!}
                                {!! Form::hidden('usersId', $video->userId) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                'data-url'=>route('videosDelete'), 'data-form'=>true, 'data-id-form'=>'textDelete'.$video->videosId]) !!}
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