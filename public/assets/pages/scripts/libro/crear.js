$(document).ready(function (){
    Biblioteca.validacionGeneral('form-general');
    $('#foto').fileinput({
        language: 'es',
        allowedFileExtensions:['jpg','jpeg','png'],
        maxFileSize: 1000,
        showUpload: false,
        showClose: false,
        InitialPreviewAsData: true,
        dropZoneEnabled: false,
        theme: "fas",
    });
});