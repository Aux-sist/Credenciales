<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title>Cropper.js</title>
    @section("styles")
<link href="{{asset('assets/js/bootsprat-fileinput/css/fileinput.min.css')}}" rel="stylesheet" type="text/css" />
<link href="/assets/js/cropper-js/cropper.css" rel="stylesheet" type="text/css" />
@endsection
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="/assets/js/cropper-js/cropper.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container">
    <form class="form">
        <div class="mb-3">
            <label for="file-upload" class="form-label">Upload Images</label><br/>
            <input type="file" id="file-upload" class="image">
        </div>
    </form>
    <!-- en este img se mostrará el archivo despues de haberlo subido-->
    <img src="#" id="img-uploaded" style="visibility: hidden;" class="img-fluid" />
    <!-- en este span se visualizará la url del archivo-->
    <span id="span-uploaded"></span>
</div>
<div class="modal fade" id="div-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Crop image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- en este img se visualizará todo el archivo seleccionado-->
                            <img id="img-original" class="img-fluid">
                        </div>
                        <div class="col-md-4">
                            <!-- en este div se mostrará la zona seleccionada, lo que quedará despues de hacer click en el boton crop-->
                            <div id="div-preview" class="preview img-fluid"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-crop">Crop</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
img {
    display: block;
    max-width: 100%;
}
.preview {
    overflow: hidden;
    width: 160px;
    height: 160px;
    margin: 10px;
    border: 1px solid #0B5ED7;
}
</style>
<script src="/assets/js/cropper-js/cropper.js"></script>
<script type="module" src="{{asset('assets/pages/scripts/libro/app.js')}}" type="text/javascript">
    //este código lo pongo más abajo
</script>
</body>
</html>