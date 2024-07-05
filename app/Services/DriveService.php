<?php

namespace App\Services;

use Google\Client;
use Google\Exception;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\DriveFileCapabilities;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\App;

class DriveService
{
    /**
     *
     * @var Drive
     */
    private $driveService; 
    /**
     * @var string
     */
    private $archivoCliente = 'client_ugmdocs.json';
    /**
     *
     * @var string
     */
    private $dominio = 'ugm.mx';

    /**
     * @throws PathNotFoundException
     * @throws Exception
     * @throws FileNotFoundException
     * @throws GoogleSystemNotFoundException
     */
    public function iniciarConfiguracion(): void
    {

        $cliente = new Client();
        //$cliente->setAuthConfig($this->archivoCliente());
        $cliente->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $cliente->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));

        $cliente->setHostedDomain($this->dominio);
        $cliente->setAccessType('offline');
        $cliente->setHttpClient(new GuzzleClient([
            'headers' => [
                'Accept-Encoding' => 'gzip, deflate',
                'User-Agent' => 'UGM Docs (gzip)',
            ]
        ]));
        $cliente->addScope(Drive::DRIVE_FILE);
        $cliente->setAccessToken(['access_token' => env('GOOGLE_DRIVE_ACCESS_TOKEN'),
                                'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
                                'expires_in' => env('GOOGLE_EXPIRE_IN'),]);
        $this->driveService = new Drive($cliente);
    }

  /*  public function crearDirectorio(string $nombre, string $parentId = ''): DriveFile
    {
        $archivo = new Drive\DriveFile();
        $archivo->setName($nombre);
        $archivo->setMimeType('application/vnd.google-apps.folder');

        if ($parentId) {
            $archivo->setParents([$parentId]);
        }

        return $this->driveService->files->create($archivo);
    }*/

    public function subirArchivo(string $foto, string $rutaArchivo /*,string $parentId = ''*/): DriveFile
    {
        $archivo = new Drive\DriveFile();
        $archivo->setName($foto);
        
        /*
        if ($parentId) {
            $archivo->setParents([$parentId]);
        }*/

        return $this->driveService->files->create($archivo, [
            'data' => file_get_contents($rutaArchivo),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart',
        ]);
    }

    public function buscarArchivo(string $idDrive, bool $media = false)
    {
        $params = [];

        if ($media) {
            $params = [
                'alt' => 'media',
            ];
        }

        return $this->driveService->files->get($idDrive, $params);
    }

    public function buscarFolder(string $idDrive): DriveFile
    {
        return $this->driveService->files->get($idDrive, [
            'fields' => 'webViewLink,webContentLink',
        ]);
    }

    public function cambiarPermisos(string $idDrive, string $role): void
    {
        $listPermission = $this->driveService->permissions->listPermissions($idDrive, [
            'fields' => 'permissionId',
        ]);
        $listPermission = $listPermission->getPermissions();

        foreach ($listPermission as $permission) {
            $permission->setRole($role);

            $this->driveService->permissions->update($idDrive, $permission->getId(), $permission);
        }
    }

    public function revocarPermisos(string $idDrive): void
    {
        $listPermission = $this->driveService->permissions->listPermissions($idDrive, [
            'fields' => 'permissionId',
        ]);
        $listPermission = $listPermission->getPermissions();

        foreach ($listPermission as $permission) {
            $this->driveService->permissions->delete($idDrive, $permission->getId());
        }
    }

    public function verPermisos(string $idDrive): DriveFileCapabilities
    {
        $file = $this->driveService->files->get($idDrive, [
            'fields' => 'capabilities',
        ]);

        return $file->getCapabilities();
    }

    public function eliminarArchivo(string $idDrive)
    {
        return $this->driveService->files->delete($idDrive);
    }

    public function enviarAPapelera(string $idDrive): DriveFile
    {
        $archivo = new Drive\DriveFile();
        $archivo->setTrashed(true);

        return $this->driveService->files->update($idDrive, $archivo);
    }

   /* public function visualizar(string $idDrive): void
    {
        $metaArchivo = $this->buscarArchivo($idDrive);
        $mime = $metaArchivo->getMimeType();
        $name = $metaArchivo->getName();
        $archivo = $this->buscarArchivo($idDrive, true);

        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename='$name'");

        $out = fopen('php://output', 'wb');

        fwrite($out, $archivo->getBody());
        fclose($out);
    }*/

    public function archivo(string $idDrive, array $params = [])
    {
        return $this->driveService->files->get($idDrive, $params);
    }

    /**
     * Set the value of archivoCliente
     */
    public function setArchivoCliente(string $archivoCliente): void
    {
        $this->archivoCliente = $archivoCliente;
    }
    
    /**
     * Set the value of dominio
     */
    public function setDominio(string $dominio): void
    {
        $this->dominio = $dominio;
    }

}
