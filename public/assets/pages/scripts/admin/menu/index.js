$(document).ready(function(){
    $('#nestable').nestable().on('change', function(){
        const data={
            menu: window.JSON.stringify($('#nestable').nestable('serialize')),
            _token: $('input[name=_token]').val()
        };
        $.ajax({
            url:'',
            type:'POST',
            dataType:'JSON',
            data:data,
            success: function(respuesta){

            }
        });
    })
$('.eliminar-menu').on('click', function(event){
    event.preventDefault();
    const url = $(this).attr('href');
    swal({
        title: 'Deseas borrar este registro?',
        text: 'Esto no podra deshacerse',
        icon: 'warning',
        buttons: {
            cancel: "Cancelar",
            confirm: "Aceptar"
        }
    }).then((value)=>{
        if (value){
            window.location.href = url;
        }
    });
    })

    $('#nestable').nestable('expandAll');
})