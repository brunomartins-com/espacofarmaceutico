@extends('admin.sidebar-template')

@section('title', 'Dados do Site | ')

@section('page-content')
@parent
<!-- Main Container -->
<main id="main-container">
    <!-- Page Header -->
    <div class="content bg-gray-lighter">
        <div class="row items-push">
            <div class="col-sm-7">
                <h1 class="page-heading">
                    Dados do Site <small></small>
                </h1>
            </div>
            <div class="col-sm-5 text-right hidden-xs">
                <ol class="breadcrumb push-10-t">
                    <li>Dados do Site</li>
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
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">Editar</h3>
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
                <!-- .block-content -->
                <div class="block-content block-content-full">
                    {!! Form::open([
                            'id' => 'websiteSettings',
                            'method' => 'put',
                            'class' => 'form-horizontal push-20-t',
                            'enctype' => 'multipart/form-data',
                            'url' => route('websiteSettingsPut')
                            ])
                    !!}
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('title', 'Título *') !!}
                                {!! Form::text('title', $websiteSettings->title, ['class'=>'form-control', 'id'=>'title', 'maxlength'=>50]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-10">
                            <div class="form-input">
                                {!! Form::label('costumerServicePhone', 'Telefone SAC *') !!}
                                {!! Form::text('costumerServicePhone', $websiteSettings->costumerServicePhone, ['class'=>'form-control', 'id'=>'costumerServicePhone', 'maxlength'=>30]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-4 col-md-6 col-sm-8 col-xs-10">
                            <div class="form-input">
                                {!! Form::label('phone', 'Telefone') !!}
                                {!! Form::text('phone', $websiteSettings->phone, ['class'=>'form-control', 'id'=>'phone', 'maxlength'=>30]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('address', 'Endereço *') !!}
                                {!! Form::text('address', $websiteSettings->address, ['class'=>'form-control', 'id'=>'address', 'maxlength'=>200]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('email', 'E-mail *') !!}
                                {!! Form::text('email', $websiteSettings->email, ['class'=>'form-control', 'id'=>'email', 'maxlength'=>50]) !!}
                            </div>
                        </div>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    {!! Form::label('facebook', 'Facebook') !!}
                                    {!! Form::text('facebook', $websiteSettings->facebook, ['class'=>'form-control', 'id'=>'facebook', 'maxlength'=>255]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    {!! Form::label('twitter', 'Twitter') !!}
                                    {!! Form::text('twitter', $websiteSettings->twitter, ['class'=>'form-control', 'id'=>'twitter', 'maxlength'=>255]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    {!! Form::label('youtube', 'Youtube') !!}
                                    {!! Form::text('youtube', $websiteSettings->youtube, ['class'=>'form-control', 'id'=>'youtube', 'maxlength'=>255]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    {!! Form::label('instagram', 'Instagram') !!}
                                    {!! Form::text('instagram', $websiteSettings->instagram, ['class'=>'form-control', 'id'=>'instagram', 'maxlength'=>255]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    {!! Form::label('linkedin', 'LinkedIn') !!}
                                    {!! Form::text('linkedin', $websiteSettings->linkedin, ['class'=>'form-control', 'id'=>'linkedin', 'maxlength'=>255]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('googleAnalytics', 'Google Analytics') !!}
                                {!! Form::textarea('googleAnalytics', $websiteSettings->googleAnalytics, ['class'=>'form-control', 'id'=>'googleAnalytics']) !!}
                            </div>
                        </div>
                    </div>
                    <br /><br />
                    <div class="form-group">
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('currentFavicon', 'Ícone') !!}
                            <div class="clearfix"></div>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new" style="max-width:{{ $imageDetails['faviconWidth'] }}px; max-height:{{ $imageDetails['faviconHeight'] }};">
                                    <img src="{!! url($imageDetails['folder'].$websiteSettings->favicon)."?".time() !!}" alt="{{ $websiteSettings->title }}" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width:{{ $imageDetails['faviconWidth'] }}px; max-height:{{ $imageDetails['faviconHeight'] }}; border: 0; padding: 0; border-radius: 0;"></div>
                                <div class="font-size-10 push-10-t">Tamanho: {{ $imageDetails['faviconWidth']." x ".$imageDetails['faviconHeight'] }} pixels / Formatos: .png ou .gif</div>
                                <div class="push-20-t">
                                    <span class="btn btn-primary btn-xs btn-file">
                                        <span class="fileupload-new">Trocar</span>
                                        <span class="fileupload-exists">Trocar</span>
                                        {!! Form::hidden('currentFavicon', $websiteSettings->favicon) !!}
                                        {!! Form::file('favicon', ['class'=>'form-control', 'id'=>'favicon', 'accept'=>'image/png,image/gif']) !!}
                                    </span>
                                    <a href="#" class="btn btn-primary btn-xs fileupload-exists" data-dismiss="fileupload" style="margin-left: 30px;">Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br /><br />
                    <div class="form-group">
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('currentAvatar', 'Avatar') !!}
                            <div class="clearfix"></div>
                            <div class="img-container hidden">
                                <img />
                            </div>
                            <div class="img-current">
                                <img src="{!! url($imageDetails['folder'].$websiteSettings->avatar)."?".time() !!}" alt="{{ $websiteSettings->title }}" />
                            </div>
                            <div class="font-size-10 push-10-t">Tamanho: {{ $imageDetails['avatarWidth']." x ".$imageDetails['avatarHeight'] }} pixels</div>
                            <br>
                            <label class="btn btn-primary btn-xs btn-upload" for="avatar" title="Upload">
                                {!! Form::hidden('currentAvatar', $websiteSettings->avatar) !!}
                                {!! Form::hidden('avatarPositionX', '') !!}
                                {!! Form::hidden('avatarPositionY', '') !!}
                                {!! Form::hidden('avatarCropAreaW', '') !!}
                                {!! Form::hidden('avatarCropAreaH', '') !!}
                                {!! Form::file('avatar', ['class'=>'sr-only', 'id'=>'avatar', 'accept'=>'image/*', 'data-crop'=>true]) !!}
                                <span class="docs-tooltip" title="Trocar">Trocar</span>
                            </label>
                            <label class="btn btn-primary btn-xs btn-cancel-upload hidden" title="Cancel">Cancelar</label>
                        </div>
                    </div>
                    <br /><br />
                    <div class="form-group">
                        <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('currentAppleTouchIcon', 'Apple Touch Icon') !!}
                            <div class="clearfix"></div>
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <div class="fileupload-new" style="max-width:{{ $imageDetails['appleTouchIconWidth'] }}px; max-height:{{ $imageDetails['appleTouchIconHeight'] }}px;">
                                    <img src="{!! url($imageDetails['folder'].$websiteSettings->appleTouchIcon)."?".time() !!}" alt="{{ $websiteSettings->title }}" />
                                </div>
                                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width:{{ $imageDetails['appleTouchIconWidth'] }}px; max-height:{{ $imageDetails['appleTouchIconHeight'] }}px; border-radius: 0; border:0; padding: 0;"></div>
                                <div class="font-size-10 push-10-t">Tamanho: {{ $imageDetails['appleTouchIconWidth']." x ".$imageDetails['appleTouchIconHeight'] }} pixels / Somente .png</div>
                                <div class="push-20-t">
                                    <span class="btn btn-primary btn-xs btn-file">
                                        <span class="fileupload-new">Trocar</span>
                                        <span class="fileupload-exists">Trocar</span>
                                        {!! Form::hidden('currentAppleTouchIcon', $websiteSettings->appleTouchIcon) !!}
                                        {!! Form::file('appleTouchIcon', ['id'=>'appleTouchIcon', 'accept'=>'image/png']) !!}
                                    </span>
                                    <a href="#" class="btn btn-primary btn-xs fileupload-exists" data-dismiss="fileupload" style="margin-left: 30px;">Cancelar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="form-group">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('websiteOk', 'Liberar Site') !!}
                                <label class="css-input switch switch-primary">
                                    <input class="access" name="websiteOk" id="websiteOk" type="checkbox" value="1" @if($websiteSettings->websiteOk == 1) checked @endif />
                                    <span></span>
                                </label>
                            </div>
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
            'title': {
                required: true
            },
            'costumerServicePhone': {
                required: true
            },
            'address': {
                required: true
            },
            'email': {
                required: true,
                email: true
            }
        },
        messages: {
            'title': {
                required: 'Informe o título do site'
            },
            'costumerServicePhone': {
                required: 'Insira o telefone SAC'
            },
            'address': {
                required: 'Insira o endereço'
            },
            'email': {
                required: 'Informe o e-mail padrão do site',
                email: 'Informe um e-mail válido'
            }
        }
    });
});
$('.img-container > img').cropper({
    aspectRatio: <?=($imageDetails['avatarWidth'])/($imageDetails['avatarHeight']);?>,
    autoCropArea: 1,
    minContainerWidth:<?=$imageDetails['avatarWidth'];?>,
    minContainerHeight:<?=$imageDetails['avatarHeight'];?>,
    minCropBoxWidth:<?=$imageDetails['avatarWidth'];?>,
    minCropBoxHeight:<?=$imageDetails['avatarHeight'];?>,
    mouseWheelZoom:false,
    crop: function(e) {
        $("input[name=avatarPositionX]").val(Math.round(e.x));
        $("input[name=avatarPositionY]").val(Math.round(e.y));
        $("input[name=avatarCropAreaW]").val(Math.round(e.width));
        $("input[name=avatarCropAreaH]").val(Math.round(e.height));
    }
});
$('.btn-cancel-upload').click(function(){
    $('.btn-upload').removeClass('hidden');
    $('.btn-cancel-upload').addClass('hidden');
    $('.img-current').removeClass('hidden');
    $('.img-container > img').attr('src', '');
    $('.img-container').addClass('hidden');
    $('input[type=file]#avatar').val('');
});
$(function () {
    var $image = $('.img-container > img');
    // Import image
    var $inputImage = $('input[type=file]#avatar');
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
