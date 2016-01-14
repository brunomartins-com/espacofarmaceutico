@extends('admin.sidebar-template')

@section('title', 'Vencedores | ')

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
                    Vencedores <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li>Vencedores</li>
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
                    @if($qtdWinners < 6)
                    {!! Form::button('<i class="fa fa-plus"></i> Adicionar', ['class'=>'btn btn-primary', 'onclick'=>'window.open(\''.route('winnersAdd').'\', \'_self\');']) !!}
                    @endif
                </h3>
            </div>
            <div class="block">
                @if (Session::has('success'))
                <div class="block-content">
                    <div class="alert alert-info alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {!! Session::get('success') !!}
                    </div>
                </div>
                @endif
                @if (count($errors) > 0)
                <div class="block-content">
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif
                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                    <li class="active">
                        <a href="#photos">Fotos</a>
                    </li>
                    <li class="">
                        <a href="#videos">Vídeos</a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="photos">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                        <table class="table table-bordered table-striped js-dataTable-full">
                            <thead>
                            <tr>
                                <th width="60">Posição</th>
                                <th width="180">Foto</th>
                                <th>Participante</th>
                                <th class="text-center sorting-none" style="width: 70px;">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($photoWinners as $photoWinner)
                                <tr>
                                    <td class="font-w600">{{ $photoWinner->quantityVotes }}º Lugar</td>
                                    <td><img src="{{ asset('assets/images/_upload/fotos/'.$photoWinner->usersId.'/thumb_'.$photoWinner->photo) }}" height="100" alt="{{ $photoWinner->babyName }}" /></td>
                                    <td>
                                        <span class="font-w600">Bebê: {{ $photoWinner->babyName }}</span>
                                        <br />
                                        <span class="font-w600">Responsável: {{ $photoWinner->name }}</span>
                                        <br />
                                        {{ $photoWinner->city." - ".$photoWinner->state }}
                                    </td>
                                    <td class="text-center">
                                        {!! Form::open([
                                            'id' => 'textDelete'.$photoWinner->photosId,
                                            'method' => 'put',
                                            'enctype' => 'multipart/form-data',
                                            'url' => ''
                                            ])
                                        !!}
                                        {!! Form::hidden('table', 'photos') !!}
                                        {!! Form::hidden('photosId', $photoWinner->photosId) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                        'data-url'=>route('winnersDelete'), 'data-form'=>true, 'data-id-form'=>'textDelete'.$photoWinner->photosId]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="videos">
                        <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                        <table class="table table-bordered table-striped js-dataTable-full">
                            <thead>
                            <tr>
                                <th width="60">Posição</th>
                                <th width="180">Vídeo</th>
                                <th>Participante</th>
                                <th class="text-center sorting-none" style="width: 70px;">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($videoWinners as $videoWinner)
                                <tr>
                                    <td class="font-w600">{{ $videoWinner->quantityVotes }}º Lugar</td>
                                    <td><img src="{{ $videoWinner->image }}" height="100" alt="{{ $videoWinner->name }}" /></td>
                                    <td>
                                        <span class="font-w600">Bebê: {{ $videoWinner->babyName }}</span>
                                        <br />
                                        <span class="font-w600">Responsável: {{ $videoWinner->name }}</span>
                                        <br />
                                        {{ $videoWinner->city." - ".$videoWinner->state }}
                                    </td>
                                    <td class="text-center">
                                        {!! Form::open([
                                            'id' => 'textDelete'.$videoWinner->videosId,
                                            'method' => 'put',
                                            'enctype' => 'multipart/form-data',
                                            'url' => ''
                                            ])
                                        !!}
                                        {!! Form::hidden('table', 'videos') !!}
                                        {!! Form::hidden('videosId', $videoWinner->videosId) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                        'data-url'=>route('winnersDelete'), 'data-form'=>true, 'data-id-form'=>'textDelete'.$videoWinner->videosId]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
<!-- Page JS Plugins -->
<script src="{{ asset('assets/admin/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<!-- Page JS Code -->
<script src="{{ asset('assets/admin/js/pages/base_tables_datatables.js') }}"></script>
<!-- Personalizing dataTable -->
<script>
jQuery(function(){
    jQuery('.js-dataTable-full').dataTable({
        order: [[0, 'asc']],
        columnDefs: [ { orderable: false, targets: 'sorting-none' } ],
        bPaginate: false,
        language: {
            'url': '<?php echo asset('assets/json/dataTablesPT-BR.json'); ?>'
        }
    });
    //TABS
    var hash = window.location.hash;
    hash && $('ul.nav a[href="' + hash + '"]').tab('show');

    $('.nav-tabs a').click(function (e) {
        $(this).tab('show');
        var scrollmem = $('body').scrollTop();
        window.location.hash = this.hash;
        $('html,body').scrollTop(scrollmem);
    });
});
</script>
@stop
