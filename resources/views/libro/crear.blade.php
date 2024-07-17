@extends('theme.lte.layout')
@section('titulo')
Libros
@endsection

@section("styles")
<link href="{{asset('assets/js/bootsprat-fileinput/css/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
<link href="/assets/js/cropper-js/cropper.css" rel="stylesheet" type="text/css" />
@endsection

@section("scriptsPlugins")
<script src="{{asset('assets/js/bootsprat-fileinput/js/fileinput.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootsprat-fileinput/js/locales/es.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootsprat-fileinput/themes/fa6/theme.min.js')}}" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" type="text/javascript"></script>
@endsection

@section("scripts")
<script src="{{asset('assets/pages/scripts/libro/crear.js')}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
    @include('includes.form-error')
        @include('includes.mensaje')
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Crear Libro</h3>
                <a href="{{route('libro')}}" class="btn btn-info btn-sm pull-right">Listado</a>
            </div> 
            <form action='{{route("guardar_libro")}}' id="form-general"  class="form-horizontal" method="POST" enctype="multipart/form-data">
                @csrf 
            <div class="box-body">
                    @include('libro.form')
            </div>
                <div class="box-footer">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        @include("includes.boton-form-crear")
                    </div>
                </div>
                <div class="box-body">
                    
            </div>
            </form>
        </div>
    </div>
    @include('libro.ver')
</div>
<script src="/assets/js/cropper-js/cropper.js"></script>
<script type="module" src="{{asset('assets/pages/scripts/libro/label.js')}}" type="text/javascript"></script>
@endsection