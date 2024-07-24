const $file = document.getElementById("foto")
const $image = document.getElementById("img-original")
const $modal = document.getElementById("div-modal")

//si deseamos interactuar con el modal usando los metodos nativos de bootstrap5
//debemos construirlo pasando el elemento. En nuestro caso .show() y .hide()
const objmodal = new bootstrap.Modal($modal, {  
    keyboard: false
})

$file.addEventListener("change", function (e) {
    const load_image = function (url){
        $image.src = url
        objmodal.show()
    }

    const files = e.target.files

    if(files && files.length>0) {
        const objfile = files[0]
        
        if (URL){
            //crea una url del estilo: blob:http://localhost:1024/129e832d-2545-471f-8e70-20355d8e33eb
            const url = URL.createObjectURL(objfile)
            load_image(url)
        }
        else if (FileReader) {
            const reader = new FileReader()
            reader.onload = function (e) {
                load_image(reader.result)
            }
            reader.readAsDataURL(objfile)
        }
    }
})

const $btncrop = document.getElementById("btn-crop")

/*$btncrop.addEventListener("click", function (){
    const canvas = cropper.getCroppedCanvas()
    canvas.toBlob(function (blob){
        const reader = new FileReader()
        reader.readAsDataURL(blob)
        console.log(blob)
        reader.onloadend = function (){
            const base64data = reader.result
            console.log("base64data", base64data)
            const arr = base64data.split(',');
            const mime=arr[0].match(/:(.*?);/)[1]
            const data=arr[1]
            const dataStr = window.atob(data);
                    let n = dataStr.length;
                    const dataArr = new Uint8Array(n);
                    while(n--)
            {
                dataArr[n]=dataStr.charCodeAt(n)
            }
                var file=new File([dataArr], 'FileR.png',{type:mime})
                console.log(file)

            var formData = new FormData()
            formData.append('image', file)

            var basePath = $('body').data('basePath')
            console.log("path", basePath)
            $.ajax({
               
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: formData,
                url: basePath+'/crop_image',
                contentType: false,
                processData: false,
                success: function(data) { 
                    $("#div-modal").modal('hide')
                    alert("imagen subida")
                },
            })
        }
    })
})*/

$btncrop.addEventListener("click", function (){
    const canvas = cropper.getCroppedCanvas()
    canvas.toBlob(function (blob){
        const reader = new FileReader()
        reader.readAsDataURL(blob)
        console.log(blob)
        reader.onloadend = function (){
            const base64data = reader.result
            console.log("base64data", base64data)
            const arr = base64data.split(',');
            const mime=arr[0].match(/:(.*?);/)[1]
            const data=arr[1]
            const dataStr = window.atob(data);
                    let n = dataStr.length;
                    const dataArr = new Uint8Array(n);
                    while(n--)
            {
                dataArr[n]=dataStr.charCodeAt(n)
            }
                var file=new File([dataArr], 'FileR.png',{type:mime})
                console.log(file)
                formData(file)
                resize(file)
            //base64ToImage(base64data)

        }
    })
})

function base64ToImage(base64data){
    const arr = base64data.split(',');
    const mime=arr[0].match(/:(.*?);/)[1]
    const data=arr[1]
    const dataStr = window.atob(data);
            let n = dataStr.length;
            const dataArr = new Uint8Array(n);
            while(n--)
    {
        dataArr[n]=dataStr.charCodeAt(n)
    }
        var file=new File([dataArr], 'FileM.png',{type:mime})
        console.log(file)
        formData(file)
}

function resize(file){
    const WITDH = 80
    let reader = new FileReader
    reader.readAsDataURL(file)
    reader.onload = (event) =>{
        let image_url = event.target.result
        let image = document.createElement("img")
        image.src = image_url
        image.onload = (e) => {
            let canvas = document.createElement("canvas")
            let ratio = WITDH / e.target.width
            canvas.width = WITDH
            canvas.height = e.target.height * ratio
            
            const context = canvas.getContext("2d")
            context.drawImage(image, 0, 0, canvas.width, canvas.height)

            let base64data=canvas.toDataURL('image/png',90)
            
            base64ToImage(base64data)
        }
    }
}

function formData(file){
    var formData = new FormData()
    formData.append('image', file)
    ajax(formData)
}

function ajax(formData){
    var basePath = $('body').data('basePath')
    console.log("path", basePath)
    $.ajax({
               
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        data: formData,
        url: basePath+'/crop_image',
        contentType: false,
        processData: false,
        success: function(data) { 
            $("#div-modal").modal('hide')
        },
    })
   
}

let cropper = null
$modal.addEventListener("shown.bs.modal", function (){
    console.log("modal.on-show")
    cropper = new Cropper($image, {
        preview: document.getElementById("div-preview"),
        viewMode: 3,
        aspectRatio: 4/5,
    })
})

$modal.addEventListener("hidden.bs.modal", function (){
    console.log("modal.on-hide")
    $('.modal-backdrop').remove();
    cropper.destroy()
    cropper = null
})   