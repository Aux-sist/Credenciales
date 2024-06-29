@extends("theme.$theme.layout")
@section('titulo')
Permisos
@endsection

@section("scripts")
<script src= '{{asset("assets/pages/scripts/admin/index.js")}}'type="text/javascripts"></script>
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
    @include('includes.mensaje')
    <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Permisos</h3>
              <a href="{{route('crear_permiso')}}" class="btn btn-success btn-sm pull-right">Crear Permiso</a>
            </div>
            <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-bordered table-hover " id="tabla-data">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th class="width70"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisos as $permiso)
                    <tr>
                        <th>{{$permiso->id}}</th>
                        <th>{{$permiso->nombre}}</th>
                        <th>{{$permiso->slug}}</th>
                        <th> 
                            <a href="{{route('editar_permiso', ['id'=> $permiso -> id])}}" class="btn-accion-table tooltipsC" tittle="Editar el registro">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{route('eliminar_permiso',  ['id' => $permiso->id])}}" class="d-inline form-eliminar" method="POST">
                                        @csrf  @method("delete")
                                        <button type="submit" class="btn-accion-tabla eliminar tooltipsC" title="Eliminar este registro">
                                            <i class="fa fa-times-circle text-danger"></i>
                                        </button>
                        </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
    </div>
    </div>
</div>
@endsection