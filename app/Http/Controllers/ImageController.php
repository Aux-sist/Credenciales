<?php

namespace App\Http\Controllers;

use App\Services\DriveService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function crop_image(Request $request)
    {
        $file=$request->file("image");
        $names=$file->getClientOriginalName();
        $file->move(storage_path("imagen"),$names);
        /*$driveService = new DriveService();
        $driveService->iniciarConfiguracion();
        $driveFolder= $driveService->crearDirectorio($names,);
        $folderID=$driveFolder->getId();
        $name=$names.'_original';
        $archivosubido= $driveService->subirArchivo($name, storage_path("imagen")."/".$names,$folderID);*/
    }
}