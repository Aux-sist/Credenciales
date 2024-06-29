$(document).ready(function (){
    Biblioteca.validacionGeneral('forn-general');
    $('#foto').fileinput({
        language: 'es',
        allowedFileExtensions:['jpg','jpeg','png'],
        maxFileSize: 1080,
        showUpload: false,
        showClose: false,
        InitialPreviewAsData: true,
        dropZoneEnable: false,
        theme: "fa",
    });
})