@extends('theme.lte.layout')
@section('titulo')
Libros
@endsection

@section("styles")
<link href="{{asset('assets/js/bootsprat-fileinput/css/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section("scriptsPlugins")
<script src="{{asset('assets/js/bootsprat-fileinput/js/fileinput.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootsprat-fileinput/js/locales/es.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/bootsprat-fileinput/themes/fa5/theme.min.js')}}" type="text/javascript"></script>
@endsection

@section("scripts")
<script src="{{asset('assets/pages/scripts/libro/crear.js')}}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.mensaje')
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Libros</h3>
                <a href="{{route('libro')}}" class="btn btn-info btn-sm pull-right">Listado</a>
            </div> 
            <form action='{{route("actualizar_libro", $data->id)}}' id="form-general"  
            class="form-horizontal" method="POST" enctype="multipart/form-data">
                @csrf @method("put")
            <div class="box-body">
                    @include('libro.form')
            </div>
                <div class="box-footer">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        @include("includes.boton-form-editar")
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection