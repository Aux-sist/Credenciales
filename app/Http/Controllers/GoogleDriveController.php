<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Services\DriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoogleDriveController extends Controller
{
    public function guardar(Request $request)
    {
        $archivo = $request->file('foto_up');
        $archivo->move(storage_path("imagen"),$archivo->getClientOriginalName());
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $archivosubido= $driveService->subirArchivo($archivo->getClientOriginalName(), 
                                        storage_path("imagen")."/".$archivo->getClientOriginalName());
        unlink(storage_path("imagen")."/".$archivo->getClientOriginalName());
        $libro = new Libro();
        $libro->create(['titulo'=>$request->titulo , 
        'isbn'=>$request->isbn, 'autor'=>$request->autor,
         'cantidad'=>$request->cantidad, 'editorial'=>$request->editorial, 'foto'=> $archivosubido->getId()]);
        return redirect()->route('libro')->with('mensaje','el libro se ha creado');
    }

    public function mostrar(Request $request)
    {
        /*
        $idDrive = DB::table('libro')->where('id', $request->id)->value('foto');        
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveService->visualizar($idDrive); 
        */
    }

    public function actualizar(Request $request, $id)
    {
        $idDrive = DB::table('libro')->where('id', $request->id)->value('foto');       
        //$idDrive = Libro::select(['foto'])->where('id', $request->id)->first(); 
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveService->eliminarArchivo($idDrive); 
        
        $archivo = $request->file('foto_up');
        $archivo->move(storage_path("imagen"),$archivo->getClientOriginalName());
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $archivosubido= $driveService->subirArchivo($archivo->getClientOriginalName(), 
                                        storage_path("imagen")."/".$archivo->getClientOriginalName());
        unlink(storage_path("imagen")."/".$archivo->getClientOriginalName());
        new Libro();
        $libro =Libro::findOrFail($id);
        $libro->update(['titulo'=>$request->titulo , 
        'isbn'=>$request->isbn, 'autor'=>$request->autor,
         'cantidad'=>$request->cantidad, 'editorial'=>$request->editorial, 'foto'=> $archivosubido->getId()]);
        return redirect()->route('libro')->with('mensaje','El libro se actualizo correctamente');
    }

    public function eliminar(Request $request, $id)
    {
        $idDrive = DB::table('libro')->where('id', $request->id)->value('foto');        
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveService->eliminarArchivo($idDrive); 
        new Libro();
        Libro::findOrFail($id);
        if (Libro::destroy($id)){
            return response()->json(['mensaje'=>'ok']);
        } else {
            return response()->json(['mensaje'=>'ng']);
        }
    }
}
