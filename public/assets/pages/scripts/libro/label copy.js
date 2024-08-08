const $file = document.getElementById("foto")
const $image = document.getElementById("img-original")
const imageContainer = document.getElementById('imageContainer')
const $modal = document.getElementById("div-modal")
//si deseamos interactuar con el modal usando los metodos nativos de bootstrap5
//debemos construirlo pasando el elemento. En nuestro caso .show() y .hide()
const objmodal = new bootstrap.Modal($modal, {
    keyboard: false
})

$file.addEventListener("change", function (e) {

    const load_image = function (url){
        $image.src = url
        console.log("Imagen:", $image)

        objmodal.show()
    }

    const files = e.target.files

    if(files && files.length>0) {
        const objfile = files[0]
        
        if (URL){
            const url = URL.createObjectURL(objfile)
            load_image(url)
        }
        else if (FileReader) {
            const reader = new FileReader()
            reader.onload = function (e) {
                load_image(reader.result)
                console.log("onload",reader.result)
            }
            reader.readAsDataURL(objfile)
        }
    }
})

let arrayImages = []
let originalFirstFileName = ''

$file.addEventListener("change", function (e) {
    var files = e.target.files
    arrayImages = Array.from(files).map(file => ({
       file,
       name: file.name 
    }))
    originalFirstFileName = arrayImages[0] ? arrayImages[0].name : ''
    console.log(arrayImages)
})


const $btncrop = document.getElementById("btn-crop");


$btncrop.addEventListener("click", function () {
    if(arrayImages.length>0){
    const canvas = cropper.getCroppedCanvas()
    const cropData = cropper.getData()
    console.log("btncrop canvas:", canvas)
    console.log("btncrop data:", cropData)
    console.log("---------------")
    if (originalFirstFileName) {
        toBlob(canvas, originalFirstFileName);
    }
    const imagesToProcess = arrayImages.slice(1)
    newCanvas(cropData,imagesToProcess)
    }
})

function newCanvas(cropData, imagesToProcess) {
    if(imagesToProcess.length>0){
        imagesToProcess.forEach((item)=>{
            const {file, name} = item
            const img = new Image()
            const objectURL = URL.createObjectURL(file)
            img.src = objectURL
           
            img.onload = () => {
                URL.revokeObjectURL(objectURL)
                const newCanvas = document.createElement('canvas')
                const ctx = newCanvas.getContext('2d')
                newCanvas.width = cropData.width
                newCanvas.height = cropData.height
                ctx.drawImage(img, cropData.x, cropData.y, cropData.width, cropData.height, 0, 0, cropData.width, cropData.height)
                
                console.log("newCanvas img:" , img)
                console.log("---------------")

                newCrops(cropData,newCanvas, name)
            }
            imageContainer.appendChild(img)
        })
    }
}

function newCrops(cropData,newCanvas, originalFileName) {
    console.log("newCrops canvas:", newCanvas)
    console.log("newCrops data:", cropData)
    let canvas = newCanvas
    toBlob(canvas,originalFileName)
}

function toBlob(canvas,originalFileName) {
    canvas.toBlob(function (blob) {
        const reader = new FileReader()
        reader.readAsDataURL(blob)
        reader.onloadend = function () {
            decodeBase64(reader,originalFileName)
        }
    })
}

function decodeBase64(reader,originalFileName){
    const base64data = reader.result
    const arr = base64data.split(',')
    const mime = arr[0].match(/:(.*?);/)[1]
    const data = arr[1]
    const dataStr = window.atob(data)
    let n = dataStr.length
    const dataArr = new Uint8Array(n)
    while (n--) {
        dataArr[n] = dataStr.charCodeAt(n)
    }
    const newFileName = originalFileName.replace(/\.[^/.]+$/, '') + '_Recortada.png'
    const file = new File([dataArr], newFileName, { type: mime })
    console.log("Imagen Recortada:",file)
    formData(file)
    resize(file,originalFileName)
}

function base64ToImage(base64data,originalFileName) {
    const arr = base64data.split(',')
    const mime = arr[0].match(/:(.*?);/)[1]
    const data = arr[1]
    const dataStr = window.atob(data)
    let n = dataStr.length
    const dataArr = new Uint8Array(n)
    while (n--) {
        dataArr[n] = dataStr.charCodeAt(n)
    }
    const newFileName = originalFileName.replace(/\.[^/.]+$/, '') + '_Miniatura.png'
    const file = new File([dataArr], newFileName, { type: mime })
    console.log("Imagen Miniatura:",file)
    formData(file)
}


function resize(file,originalFileName) {
    const WIDTH = 80
    let reader = new FileReader()
    reader.readAsDataURL(file)
    reader.onload = function (event) {
        let image_url = event.target.result
        let image = document.createElement("img")
        image.src = image_url
        image.onload = function (e) {
            let canvas = document.createElement("canvas")
            let ratio = WIDTH / e.target.width
            canvas.width = WIDTH
            canvas.height = e.target.height * ratio
            const context = canvas.getContext("2d")
            context.drawImage(image, 0, 0, canvas.width, canvas.height)
            let base64data = canvas.toDataURL('image/png', 90)
            base64ToImage(base64data,originalFileName)
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
    console.log("---------------")
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
        }
    })
}

let cropper = null
$modal.addEventListener("shown.bs.modal", function (){
    console.log("modal.on-show")
    console.log("---------------")
    cropper = new Cropper($image, {
        autoCrop: true,
        preview: document.getElementById("div-preview"),
        viewMode: 3,
        aspectRatio: 4/5,
    })    
    console.log("modal cropper:", cropper);

})

$modal.addEventListener("hidden.bs.modal", function (){
    console.log("modal.on-hide")
    $('.modal-backdrop').remove();
    cropper.destroy()
    cropper = null
})