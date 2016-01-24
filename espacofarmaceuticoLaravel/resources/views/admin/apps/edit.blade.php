@extends('admin.sidebar-template')

@section('title', 'Editar Aplicativo | ')

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Aplicativos <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li><a href="{{ route('apps') }}" class="text-orange" title="Aplicativos">Aplicativos</a></li>
                    <li>{{ $app->title }}</li>
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
                        <button type="button" class="btn-back" data-url="{{ route('apps') }}"><i class="si si-action-undo"></i></button>
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
                        'id' => 'apps',
                        'method' => 'put',
                        'class' => 'form-horizontal push-20-t',
                        'enctype' => 'multipart/form-data',
                        'url' => route('appsEditPut')
                        ])
                    !!}
                    {!! Form::hidden('appsId', $app->appsId) !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('title', 'Nome do Aplicativo *') !!}
                                {!! Form::text('title', $app->title, ['class'=>'form-control', 'id'=>'title', 'maxlength'=>100]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('description', 'Descrição *') !!}
                                {!! Form::text('description', $app->description, ['class'=>'form-control', 'id'=>'description', 'maxlength'=>200]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('iphoneUrl', 'URL iPhone') !!}
                                {!! Form::text('iphoneUrl', $app->iphoneUrl, ['class'=>'form-control', 'id'=>'iphoneUrl', 'placeholder'=>'http://', 'maxlength'=>255]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('ipadUrl', 'URL iPad') !!}
                                {!! Form::text('ipadUrl', $app->ipadUrl, ['class'=>'form-control', 'id'=>'ipadUrl', 'placeholder'=>'http://', 'maxlength'=>255]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('androidUrl', 'URL Android') !!}
                                {!! Form::text('androidUrl', $app->androidUrl, ['class'=>'form-control', 'id'=>'androidUrl', 'placeholder'=>'http://', 'maxlength'=>255]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group image">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            {!! Form::label('image', 'Imagem') !!}
                            <div class="clearfix"></div>
                            <div class="img-container hidden">
                                <img />
                            </div>
                            <div class="img-current">
                                <img src="{!! url($imageDetails['folder'].$app->image)."?".time() !!}" alt="{{ $app->title }}" class="img-responsive" />
                            </div>
                            <div class="font-size-10 push-10-t">Tamanho: {{ $imageDetails['imageWidth']." x ".$imageDetails['imageHeight'] }} pixels</div>
                            <br>
                            <label class="btn btn-primary btn-xs btn-upload" for="image" title="Selecionar Imagem">
                                {!! Form::hidden('imagePositionX', '') !!}
                                {!! Form::hidden('imagePositionY', '') !!}
                                {!! Form::hidden('imageCropAreaW', '') !!}
                                {!! Form::hidden('imageCropAreaH', '') !!}
                                {!! Form::hidden('currentImage', $app->image) !!}
                                {!! Form::file('image', ['class'=>'sr-only form-control', 'id'=>'image', 'accept'=>'image/*', 'data-crop'=>true]) !!}
                                <span class="docs-tooltip" title="Selecionar Imagem">Selecionar Imagem</span>
                            </label>
                            <label class="btn btn-primary btn-xs btn-cancel-upload hidden" title="Cancelar">Cancelar</label>
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
            'title': {
                required: true
            },
            'description': {
                required: true
            }
        },
        messages: {
            'title': {
                required: 'Por favor informe o nome do aplicativo'
            },
            'description': {
                required: 'Por favor informe a descrição do aplicativo'
            }
        }
    });
});
//IMAGE
$('.image .img-container > img').cropper({
    aspectRatio: <?=($imageDetails['imageWidth'])/($imageDetails['imageHeight']);?>,
    autoCropArea: 1,
    minContainerWidth:<?=$imageDetails['imageWidth'];?>,
    minContainerHeight:<?=$imageDetails['imageHeight'];?>,
    minCropBoxWidth:<?=$imageDetails['imageWidth'];?>,
    minCropBoxHeight:<?=$imageDetails['imageHeight'];?>,
    mouseWheelZoom:false,
    crop: function(e) {
        $("input[name=imagePositionX]").val(Math.round(e.x));
        $("input[name=imagePositionY]").val(Math.round(e.y));
        $("input[name=imageCropAreaW]").val(Math.round(e.width));
        $("input[name=imageCropAreaH]").val(Math.round(e.height));
    }
});
$('.image .btn-cancel-upload').click(function(){
    $('.imageImage .btn-upload').removeClass('hidden');
    $('.imageImage .btn-cancel-upload').addClass('hidden');
    $('.imageImage .img-current').removeClass('hidden');
    $('.imageImage .img-container > img').attr('src', '');
    $('.imageImage .img-container').addClass('hidden');
    $('input[type=file]#image').val('');
});
$(function () {
    var $image = $('.image .img-container > img');
    // Import image
    var $inputImage = $('input[type=file]#image');
    var URL = window.URL || window.webkitURL;
    var blobURL;

    if (URL) {
        $inputImage.change(function () {
            $('.image .btn-upload').addClass('hidden');
            $('.image .btn-cancel-upload').removeClass('hidden');
            $('.image .img-current').addClass('hidden');
            $('.image .img-container').removeClass('hidden');

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
                    $body.tooltip('Please choose an image file.', 'warning');
                }
            }
        });
    } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
    }
});
</script>
@stop
