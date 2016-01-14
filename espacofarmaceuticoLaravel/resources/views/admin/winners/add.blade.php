@extends('admin.sidebar-template')

@section('title', 'Adicionar Vencedor | ')

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
                    <li><a href="{{ route('winners') }}" title="Vencedores">Vencedores</a></li>
                    <li>Adicionar</li>
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
                        <button type="button" class="btn-back" data-url="{{ route('winners') }}"><i class="si si-action-undo"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">Adicionar</h3>
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
                            'id' => 'winners',
                            'method' => 'put',
                            'class' => 'form-horizontal push-20-t',
                            'enctype' => 'multipart/form-data',
                            'url' => route('winnersAdd')
                            ])
                    !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('category', 'Categoria *') !!}
                                <select name="category" id="category" class="form-control">
                                    <option value="">Escolha...</option>
                                    @if($countPhotoWinners < 3)
                                    <option value="photos">Fotos</option>
                                    @endif
                                    @if($countVideoWinners < 3)
                                    <option value="videos">Vídeos</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($countPhotoWinners < 3)
                    <div class="form-group box-photos hide">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('photosId', 'Fotos Finalistas *') !!}
                                <select name="photosId" id="photosId" class="form-control">
                                    <option value="">Escolha...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($countVideoWinners < 3)
                    <div class="form-group box-videos hide">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('videosId', 'Vídeos Finalistas *') !!}
                                <select name="videosId" id="videosId" class="form-control">
                                    <option value="">Escolha...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="col-lg-2 col-md-4 col-sm-6 col-xs-8">
                            <div class="form-input">
                                {!! Form::label('quantityVotes', 'Posição *') !!}
                                {!! Form::text('quantityVotes', '', ['id' => 'quantityVotes', 'class' => 'form-control', 'maxlength'=>1]) !!}
                            </div>
                        </div>
                        <div class="col-lg-10 col-md-8 col-sm-6 col-xs-4" style="padding: 30px 0 0 0;">º Lugar</div>
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
            'category': {
                required: true
            },
            'photosId': {
                required: function(element){
                    if($("#category").val() == 'photos'){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            'videosId': {
                required: function(element){
                    if($("#category").val() == 'videos'){
                        return true;
                    }else{
                        return false;
                    }
                }
            },
            'quantityVotes': {
                required: true,
                number: true
            }
        },
        messages: {
            'category': {
                required: 'Escolha a categoria do finalista'
            },
            'photosId': {
                required: 'Escolha o finalista'
            },
            'videosId': {
                required: "Escolha o finalista"
            },
            'quantityVotes': {
                required: "Informe a posição",
                number: "Somente caracteres numéricos"
            }
        }
    });
    $('select#category').change(function(){
        var table = $(this).val();
        $.ajax({
            url: '<?php echo route('winnersCategory'); ?>',
            type: 'POST',
            dataType: 'json',
            data: {'table': table, '_token': $('input[name=_token]').val()},
            beforeSend: function () {
                $('.box-photos').addClass('hide');
                $('.box-videos').addClass('hide');
                $("select#photosId").html('');
                $("select#videosId").html('');
            },
            success: function(data){
                console.log(data);
                $('.box-'+table).removeClass('hide');

                var values = '<option value="">Escolha o finalista...</option>';
                $.each(data, function (key, val) {
                    if(table == 'photos') {
                        values += '<option value="' + val.photosId + '">' + val.babyName + ' | ' + val.city + ' - ' + val.state + '</option>';
                    }else if(table == 'videos') {
                        values += '<option value="' + val.videosId + '">' + val.babyName + ' | ' + val.city + ' - ' + val.state + '</option>';

                    }
                });
                $("select#"+table+"Id").html(values);
            }
        });
    });
});
</script>
@stop
