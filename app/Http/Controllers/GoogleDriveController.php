<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidacionLibro;
use App\Models\Libro;
use App\Services\DriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GoogleDriveController extends Controller
{
    public function guardar_muchos(ValidacionLibro $request)
    {
        foreach($request->file('foto_up2') as $foto){
            $name=$foto->getClientOriginalName();
            $original = pathinfo($name, PATHINFO_FILENAME) . '_original.' . pathinfo($name, PATHINFO_EXTENSION);
            $recortado = pathinfo($name, PATHINFO_FILENAME) . '_recortada.'."png";
            $miniatura = pathinfo($name, PATHINFO_FILENAME) . '_miniatura.' . "png";
            $foto->move(storage_path("imagen"),$name);
            $driveService = new DriveService();
            $driveService->iniciarConfiguracion();
            $driveFolder= $driveService->crearDirectorio($name,);
            $folderID=$driveFolder->getId();
            $archivosubido= $driveService->subirArchivo($original, storage_path("imagen")."/".$name,$folderID);
            $archivosubidoR= $driveService->subirArchivo($recortado, storage_path("imagen")."/".$recortado,$folderID);
            $archivosubidoM= $driveService->subirArchivo($miniatura, storage_path("imagen")."/".$miniatura,$folderID);
            unlink(storage_path("imagen")."/".$name);
            unlink(storage_path("imagen")."/".$recortado);
            unlink(storage_path("imagen")."/".$miniatura);
            $libro = new Libro();
                  $libro->create([
                                  'titulo'=>$request->titulo,
                                  'isbn'=>$request->isbn,
                                  'autor'=>$request->autor,
                                  'cantidad'=>$request->cantidad,
                                  'editorial'=>$request->editorial,
                                  'foto'=>$name,
                                  'drive_id_carpeta' => $folderID,
                                  'drive_id_original'=>$archivosubido->getId(),
                                  'drive_id_recortada'=>$archivosubidoR->getId(),
                                  'drive_id_miniatura'=>$archivosubidoM->getId(),
                                 ]);
          }
        return redirect()->route('libro')->with('mensaje','Los libros se han creado');
    }
    public function guardar(ValidacionLibro $request)
    {
        $archivo = $request->file('foto_up');
        $originalName = $archivo->getClientOriginalName();
        $originalFilename = pathinfo($originalName, PATHINFO_FILENAME);
        $originalExtension = pathinfo($originalName, PATHINFO_EXTENSION);
        
        $storagePath = storage_path('imagen');
        $originalFile = $originalFilename . '_original.' . $originalExtension;
        $recortadoFile = $originalFilename . '_recortada.png';
        $miniaturaFile = $originalFilename . '_miniatura.png';
        
        $archivo->move($storagePath, $originalName);
    
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveFolder = $driveService->crearDirectorio($originalName);
        $folderID = $driveFolder->getId();
    
        $archivosubido = $driveService->subirArchivo($originalFile, "$storagePath/$originalName", $folderID);
        $archivosubidoR = $driveService->subirArchivo($recortadoFile, "$storagePath/$recortadoFile", $folderID);
        $archivosubidoM = $driveService->subirArchivo($miniaturaFile, "$storagePath/$miniaturaFile", $folderID);
    
        File::delete([
            "$storagePath/$originalName",
            "$storagePath/$recortadoFile",
            "$storagePath/$miniaturaFile",
        ]);
    
        Libro::create([
            'titulo' => $request->titulo,
            'isbn' => $request->isbn,
            'autor' => $request->autor,
            'cantidad' => $request->cantidad,
            'editorial' => $request->editorial,
            'foto' => $originalName,
            'drive_id_carpeta' => $folderID,
            'drive_id_original' => $archivosubido->getId(),
            'drive_id_recortada' => $archivosubidoR->getId(),
            'drive_id_miniatura' => $archivosubidoM->getId(),
        ]);
    
        return redirect()->route('libro')->with('mensaje', 'El libro se ha creado');
    }

    public function visualizar(Request $request)
    {
        $idDrive = DB::table('libro')->where('id', $request->id)->value('drive_id_original');
        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveService->visualizar($idDrive);
    }

    public function actualizar(Request $request, $id)
    {   //$idDrive = Libro::select(['foto'])->where('id', $request->id)->first(); 
        $libro = Libro::findOrFail($id);
        $idDrive = $libro->drive_id_carpeta;

        $driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        
        $driveService->eliminarArchivo($idDrive);

        if ($request->hasFile('foto_up')) {
            $archivo = $request->file('foto_up');
            $originalName = $archivo->getClientOriginalName();
            $originalFilename = pathinfo($originalName, PATHINFO_FILENAME);
            $originalExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            
            $storagePath = storage_path('/imagen');
            $originalFile = $originalFilename . '_original.' . $originalExtension;
            $recortadoFile = $originalFilename . '_recortada.png';
            $miniaturaFile = $originalFilename . '_miniatura.png';
            
            $archivo->move($storagePath, $originalName);

            $driveFolder = $driveService->crearDirectorio($originalName);
            $folderID = $driveFolder->getId();

            $archivosubido = $driveService->subirArchivo($originalFile, "$storagePath/$originalName", $folderID);
            $archivosubidoR = $driveService->subirArchivo($recortadoFile, "$storagePath/$recortadoFile", $folderID);
            $archivosubidoM = $driveService->subirArchivo($miniaturaFile, "$storagePath/$miniaturaFile", $folderID);

            File::delete([
                "$storagePath/$originalName",
                "$storagePath/$recortadoFile",
                "$storagePath/$miniaturaFile",
            ]);

            $libro->update([
                'titulo' => $request->titulo,
                'isbn' => $request->isbn,
                'autor' => $request->autor,
                'cantidad' => $request->cantidad,
                'editorial' => $request->editorial,
                'foto' => $originalName,
                'drive_id_carpeta' => $folderID,
                'drive_id_original' => $archivosubido->getId(),
                'drive_id_recortada' => $archivosubidoR->getId(),
                'drive_id_miniatura' => $archivosubidoM->getId(),
            ]);
        } else {
            $libro->update([
                'titulo' => $request->titulo,
                'isbn' => $request->isbn,
                'autor' => $request->autor,
                'cantidad' => $request->cantidad,
                'editorial' => $request->editorial,
            ]);
        }
        return redirect()->route('libro')->with('mensaje', 'El libro se actualizó correctamente');
    }
    public function eliminar(Request $request, $id)
    {
        $idDrive = DB::table('libro')->where('id', $request->id)->value('drive_id_carpeta');
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
