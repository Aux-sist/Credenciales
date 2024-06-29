@extends("theme.$theme.layout")
@section('titulo')
Libros
@endsection

@section("scripts")
<script src= '{{asset("assets/pages/scripts/libro/index.js")}}'type="text/javascripts"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
    @include('includes.form-error')
    @include('includes.mensaje')
    <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Libros</h3>
            <div class="box-tools pull-right">
                <a href="{{route('crear_libro')}}" class="btn btn-block btn-success btn-sm">
                    <i class="fa fa-fw fa-plus-circle"></i> Nuevo registro
                </a>
            </div>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Titulo</th>
                            <th class="width70"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $data)
                        <tr>
                            <td>{{$data->titulo}}</td>
                            <td>
                                <a href='{{route("editar_libro", ["id" => $data->id])}}' class="btn-accion-tabla tooltipsC" title="Editar este registro">
                                    <i class="fa fa-fw fa-pencil"></i>
                                </a>
                                <form action='{{route("eliminar_libro", ["id" => $data->id])}}'  class="d-inline form-eliminar" method="POST">
                                    @csrf @method("delete")
                                    <button type="submit" class="btn-accion-tabla eliminar tooltipsC" title="Eliminar este registro">
                                        <i class="fa fa-fw fa-trash text-danger"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
    </div>
</div>
@endsection