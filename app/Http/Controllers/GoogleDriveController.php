<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Services\DriveService;
use Directory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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

    public function subir(DriveService $driveService, UploadedFile $foto)
    {
        /*$extension = ".{$foto->getClientOriginalExtension()}";
        $nombreImagen = basename($foto->getClientOriginalName(), $extension);
        $rutaLocal = storage_path(). "/$nombreImagen";
        $directorioLocal = Directory::make($rutaLocal);
        $rutaImagen = "$directorioLocal/$nombreImagen";

        $foto->move($directorioLocal, $nombreImagen);


        unlink($rutaImagen);
        Directory::remove($rutaLocal);

*/
    }

}
