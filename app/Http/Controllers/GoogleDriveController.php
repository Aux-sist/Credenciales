<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionLibro;
use App\Models\Libro;
use App\Services\DriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoogleDriveController extends Controller
{
    public function guardar(ValidacionLibro $request)
    {
        $recortado = "FileR.png";
        $miniatura = "FileM.png";
        $archivo = $request->file('foto_up');
        $names=$archivo->getClientOriginalName();
        $archivo->move(storage_path("imagen"),$names);
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveFolder= $driveService->crearDirectorio($names,);
        $folderID=$driveFolder->getId();
        $name=$names.'_original';
        $archivosubido= $driveService->subirArchivo($name, storage_path("imagen")."/".$names,$folderID);
        $nameR=$names.'_recortada';
        $archivosubidoR= $driveService->subirArchivo($nameR, storage_path("imagen")."/".$recortado,$folderID);
        $nameM=$names.'_miniatura';
        $archivosubidoM= $driveService->subirArchivo($nameM, storage_path("imagen")."/".$miniatura,$folderID);

        unlink(storage_path("imagen")."/".$names);
        unlink(storage_path("imagen")."/".$recortado);
        unlink(storage_path("imagen")."/".$miniatura);

        $libro = new Libro();
        $libro->create([
                        'titulo'=>$request->titulo , 
                        'isbn'=>$request->isbn, 
                        'autor'=>$request->autor,
                        'cantidad'=>$request->cantidad, 
                        'editorial'=>$request->editorial, 
                        'foto'=>$driveFolder->getId(),
                        'drive_id_original'=>$archivosubido->getId(),
                        'drive_id_recortada'=>$archivosubidoR->getId(),
                        'drive_id_miniatura'=>$archivosubidoM->getId(),]);

        return redirect()->route('libro')->with('mensaje','el libro se ha creado');
    }

    public function mostrar(Request $request)
    {
        $idDrive = DB::table('libro')->where('id', $request->id)->value('drive_id_original');
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveService->visualizar($idDrive); 
    }

    public function actualizar(Request $request, $id)
    {   //$idDrive = Libro::select(['foto'])->where('id', $request->id)->first(); 
        $idDrive = DB::table('libro')->where('id', $request->id)->value('foto');
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveService->eliminarArchivo($idDrive);
        $recortado = "FileR.png";
        $miniatura = "FileM.png";
        $archivo = $request->file('foto_up');
        $names=$archivo->getClientOriginalName();
        $archivo->move(storage_path("imagen"),$names);
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveFolder= $driveService->crearDirectorio($names,);
        $folderID=$driveFolder->getId();
        $name=$names.'_original';
        $archivosubido= $driveService->subirArchivo($name, storage_path("imagen")."/".$names,$folderID);
        $nameR=$names.'_recortada';
        $archivosubidoR= $driveService->subirArchivo($nameR, storage_path("imagen")."/".$recortado,$folderID);
        $nameM=$names.'_miniatura';
        $archivosubidoM= $driveService->subirArchivo($nameM, storage_path("imagen")."/".$miniatura,$folderID);

        unlink(storage_path("imagen")."/".$names);
        unlink(storage_path("imagen")."/".$recortado);
        unlink(storage_path("imagen")."/".$miniatura);
        
        new Libro();
        $libro =Libro::findOrFail($id);
        $libro->update(['titulo'=>$request->titulo , 
                        'isbn'=>$request->isbn, 
                        'autor'=>$request->autor,
                        'cantidad'=>$request->cantidad, 
                        'editorial'=>$request->editorial, 
                        'drive_id_original'=>$archivosubido->getId(),
                        'drive_id_recortada'=>$archivosubidoR->getId(),
                        'drive_id_miniatura'=>$archivosubidoM->getId(),]);

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
