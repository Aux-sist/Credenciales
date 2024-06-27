$(document).ready(function(){
    $('#nestable').nestable().on('change', function(){
        const data={
            menu: window.JSON.stringify($('#nestable').nestable('serialize')),
            _token: $('input[name_token]').val()
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
    $('#nestable').nestable('expandAll');
})