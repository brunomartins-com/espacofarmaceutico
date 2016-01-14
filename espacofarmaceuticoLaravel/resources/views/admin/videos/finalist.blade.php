@extends('admin.sidebar-template')

@section('title', 'Finalista | ')

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
                    <li><a href="{{ route('videos') }}" title="Vídeos">Vídeos</a></li>
                    <li>Finalistas</li>
                    <li>Adicionar URL</li>
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
                        <button type="button" class="btn-back" data-url="{{ route('videos') }}"><i class="si si-action-undo"></i></button>
                    </li>
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">Adicionar URL - Finalista</h3>
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
                <!-- .block-content -->
                <div class="block-content block-content-full">
                    {!! Form::open([
                        'id' => 'videosFinalista',
                        'method' => 'put',
                        'class' => 'form-horizontal push-20-t',
                        'enctype' => 'multipart/form-data',
                        'url' => route('videosEdit')
                        ])
                    !!}
                    {!! Form::hidden('videosId', $video->videosId) !!}
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Bebê: ') !!}
                                {{ $video->babyName }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Responsável: ') !!}
                                {{ $video->name }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('', 'Vídeo: ') !!}
                                <a href="{{ $video->url }}" target="_blank"><img src="{{ $video->image }}" height="100" alt="{{ $video->babyName }}" /></a>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
                            <div class="form-input">
                                {!! Form::label('urlInstagram', 'URL Instagram *') !!}
                                {!! Form::text('urlInstagram', $video->urlInstagram, ['class'=>'form-control', 'id'=>'urlInstagram', 'placeholder'=>'http://', 'maxlength'=>25]) !!}
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
                'urlInstagram': {
                    required: true,
                    url: true
                }
            },
            messages: {
                'urlInstagram': {
                    required: 'Informe a URL do instagram para votação',
                    url: 'Informe uma URL válida'
                }
            }
        });
    });
</script>
@stop
