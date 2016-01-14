@extends('admin.sidebar-template')

@section('title', 'Editar Ganhador 2014 | ')

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Ganhadores 2014 <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('winners2014') }}" class="text-primary" title="Ganhadores 2014">Ganhadores 2014</a></li>
                    <li>{{ $winner->name }}</li>
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
            <div class="block-header bg-primary-darker text-white">
                <ul class="block-options">
                    <li>
                        <button type="button" class="btn-back" data-url="{{ route('winners2014') }}"><i class="si si-action-undo"></i></button>
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
                            'id' => 'winners',
                            'method' => 'put',
                            'class' => 'form-horizontal push-20-t',
                            'enctype' => 'multipart/form-data',
                            'url' => route('winners2014EditPut')
                            ])
                    !!}
                    {!! Form::hidden('winnersLastYearId', $winner->winnersLastYearId) !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('category', 'Categoria *') !!}
                                <select name="category" id="category" class="form-control">
                                    <option value="">Escolha...</option>
                                    <option value="Fotos" @if($winner->category == "Fotos"){{ 'selected' }}@endif>Fotos</option>
                                    <option value="Vídeos" @if($winner->category == "Vídeos"){{ 'selected' }}@endif>Vídeos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3 col-md-5 col-sm-7 col-xs-9">
                            <div class="form-input">
                                {!! Form::label('position', 'Posição *') !!}
                                <select name="position" id="position" class="form-control">
                                    <option value="">Escolha...</option>
                                    <option value="1" @if($winner->position == 1){{ 'selected' }}@endif>1º Lugar</option>
                                    <option value="2" @if($winner->position == 2){{ 'selected' }}@endif>2º Lugar</option>
                                    <option value="3" @if($winner->position == 3){{ 'selected' }}@endif>3º Lugar</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('name', 'Nome *') !!}
                                {!! Form::text('name', $winner->name, ['class'=>'form-control', 'id'=>'name', 'maxlength'=>50]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('city', 'Cidade *') !!}
                                {!! Form::text('city', $winner->city, ['class'=>'form-control', 'id'=>'city', 'maxlength'=>45]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-3 col-md-5 col-sm-7 col-xs-9">
                            <div class="form-input">
                                {!! Form::label('state', 'Estado *') !!}
                                {!! Form::text('state', $winner->state, ['class'=>'form-control', 'id'=>'state', 'maxlength'=>2]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('quantityVotes', 'Quatidade de Votos *') !!}
                                {!! Form::text('quantityVotes', $winner->quantityVotes, ['id' => 'quantityVotes', 'class' => 'form-control', 'maxlength'=>10]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('currentPhoto', 'Foto') !!}
                            <div class="clearfix"></div>
                            <div class="img-container hidden">
                                <img />
                            </div>
                            <div class="img-current">
                                <img src="{!! url($imageDetails['folder'].$winner->photo)."?".time() !!}" alt="{{ $winner->nome }}" />
                            </div>
                            <div class="font-size-10 push-10-t">Tamanho: {{ $imageDetails['photoWidth']." x ".$imageDetails['photoHeight'] }} pixels</div>
                            <br>
                            <label class="btn btn-primary btn-xs btn-upload" for="photo" title="Upload">
                                {!! Form::hidden('currentPhoto', $winner->photo) !!}
                                {!! Form::hidden('photoPositionX', '') !!}
                                {!! Form::hidden('photoPositionY', '') !!}
                                {!! Form::hidden('photoCropAreaW', '') !!}
                                {!! Form::hidden('photoCropAreaH', '') !!}
                                {!! Form::file('photo', ['class'=>'sr-only', 'id'=>'photo', 'accept'=>'image/*', 'data-crop'=>true]) !!}
                                <span class="docs-tooltip" title="Trocar">Selecione a Foto</span>
                            </label>
                            <label class="btn btn-primary btn-xs btn-cancel-upload hidden" title="Cancel">Cancelar</label>
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
            'position': {
                required: true
            },
            'name': {
                required: true
            },
            'city': {
                required: true
            },
            'state': {
                required: true
            },
            'quantityVotes': {
                required: true,
                number: true
            }
        },
        messages: {
            'category': {
                required: 'Escolha a categoria do ganhador'
            },
            'position': {
                required: 'Escolha a posição do ganhador'
            },
            'name': {
                required: 'Informe o nome do ganhador'
            },
            'city': {
                required: 'Informe a cidade do ganhador'
            },
            'state': {
                required: 'Informe o Estado do ganhador'
            },
            'quantityVotes': {
                required: "Informe a quantidade de votos",
                number: "Somente caracteres numéricos"
            }
        }
    });
});
$('.img-container > img').cropper({
    aspectRatio: <?=($imageDetails['photoWidth'])/($imageDetails['photoHeight']);?>,
    autoCropArea: 1,
    minContainerWidth:<?=$imageDetails['photoWidth'];?>,
    minContainerHeight:<?=$imageDetails['photoHeight'];?>,
    minCropBoxWidth:<?=$imageDetails['photoWidth'];?>,
    minCropBoxHeight:<?=$imageDetails['photoHeight'];?>,
    mouseWheelZoom:false,
    crop: function(e) {
        $("input[name=photoPositionX]").val(Math.round(e.x));
        $("input[name=photoPositionY]").val(Math.round(e.y));
        $("input[name=photoCropAreaW]").val(Math.round(e.width));
        $("input[name=photoCropAreaH]").val(Math.round(e.height));
    }
});
$('.btn-cancel-upload').click(function(){
    $('.btn-upload').removeClass('hidden');
    $('.btn-cancel-upload').addClass('hidden');
    $('.img-current').removeClass('hidden');
    $('.img-container > img').attr('src', '');
    $('.img-container').addClass('hidden');
    $('input[type=file]#photo').val('');
});
$(function () {
    var $image = $('.img-container > img');
    // Import image
    var $inputImage = $('input[type=file]#photo');
    var URL = window.URL || window.webkitURL;
    var blobURL;

    if (URL) {
        $inputImage.change(function () {
            $('.btn-upload').addClass('hidden');
            $('.btn-cancel-upload').removeClass('hidden');
            $('.img-current').addClass('hidden');
            $('.img-container').removeClass('hidden');

            var files = this.files;
            var file;

            if (!$image.data('cropper')) {
                return;
            }

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    blobURL = URL.createObjectURL(file);
                    $image.one('built.cropper', function () {
                        URL.revokeObjectURL(blobURL); // Revoke when load complete
                    }).cropper('reset').cropper('replace', blobURL);
                    //$inputImage.val('');
                } else {
                    $body.tooltip('Por favor escolha a imagem.', 'warning');
                }
            }
        });
    } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
    }
});
</script>
@stop