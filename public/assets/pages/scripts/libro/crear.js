$(document).ready(function (){
    Biblioteca.validacionGeneral('form-general');
    $('#foto').fileinput({
        language: 'es',
        allowedFileExtensions:['jpg','jpeg'],
        maxFileSize: 1000,
        showUpload: false,
        showClose: false,
        InitialPreviewAsData: true,
        dropZoneEnabled: false,
        theme: "fa",
    });
});