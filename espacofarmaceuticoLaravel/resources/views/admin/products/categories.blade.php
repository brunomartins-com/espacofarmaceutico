@extends('admin.sidebar-template')

@section('title', 'Categorias (Produtos) | ')

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
                    Produtos <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('products') }}" class="text-orange" title="Produtos">Produtos</a></li>
                    <li>Categorias</li>
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
                    {!! Form::button('<i class="fa fa-plus"></i> Adicionar', ['class'=>'btn btn-primary', 'onclick'=>'window.open(\''.route('productsCategoriesAdd').'\', \'_self\');']) !!}
                    {!! Form::button('<i class="fa fa-list"></i> Ordenar', ['class'=>'btn btn-warning push-10-l', 'onclick'=>'window.open(\''.route('productsCategoriesOrder').'\', \'_self\');']) !!}
                </h3>
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
                            <th style="width: 50px;">Id</th>
                            <th>Categoria</th>
                            <th>Tipo</th>
                            <th class="text-center sorting-none" style="width: 70px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->sortorder }}</td>
                            <td class="font-w600">{{ $category->productsCategoriesName }}</td>
                            <td>
                                @if($category->type == 0)
                                    {{ 'Categoria' }}
                                @elseif($category->type == 1)
                                    {{ 'Página' }}
                                @endif
                            </td>
                            <td class="text-center">
                                {!! Form::button('<i class="fa fa-pencil"></i>', ['title'=>'Editar', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-primary',
                                'onclick'=>'window.open(\''.route('productsCategoriesEdit', $category->productsCategoriesId).'\', \'_self\')']) !!}

                                {!! Form::open([
                                    'id' => 'textDelete'.$category->productsCategoriesId,
                                    'method' => 'delete',
                                    'enctype' => 'multipart/form-data',
                                    'url' => ''
                                    ])
                                !!}
                                {!! Form::hidden('productsCategoriesId', $category->productsCategoriesId) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['title'=>'Excluir', 'data-toggle'=>'tooltip', 'class'=>'btn btn-xs btn-danger btn-delete',
                                'data-url'=>route('productsCategoriesDelete'), 'data-msg'=>'Tem certeza que deseja deletar essa categoria?<br>Isso será um ato irreversível e todos os produtos relacionados a ela serão excluídos.', 'data-form'=>true, 'data-id-form'=>'textDelete'.$category->productsCategoriesId]) !!}
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
            order: [[0, 'asc']],
            columnDefs: [ { orderable: false, targets: 'sorting-none' } ],
            pageLength: 20,
            lengthMenu: [[25, 50, 100, 500], [25, 50, 100, 500]],
            language: {
                'url': '<?php echo asset('assets/json/dataTablesPT-BR.json'); ?>'
            }
        });
    });
</script>
@stop
