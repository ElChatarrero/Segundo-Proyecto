document.write(`<script src="${base_url}/Assets/js/plugins/JsBarcode.all.min.js"></script>`);
let rowTable = "";
let tableUniformes;

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

document.addEventListener('DOMContentLoaded',function(){
    tableUniformes = $('#tableUniformes').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
        },
        "ajax":{
            "url": " "+base_url+"/Uniformes/getUniformes",
            "dataSrc":""
        },
        "columns":[
            {"data":"id_uniforme"},
            {"data":"codigo"},
            {"data":"nombre"},
            {"data":"stock"},
            {"data":"precio"},
            {"data":"status"},           
            {"data":"options"}
        ],
        "columnDefs":[
            {'className': "textcenter", "targets": [3] },
            {'className': "textright", "targets": [4] },
            {'className': "textcenter", "targets": [5] },
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"Exportar a Excel",
                "className": "btn btn-success",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Exportar a PDF",
                "className": "btn btn-danger",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Exportar a CSV",
                "className": "btn btn-info",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    if(document.querySelector("#formUniformes")){
        let formUniformes = document.querySelector("#formUniformes");
        formUniformes.onsubmit = function(e){
            e.preventDefault();

            let strNombre = document.querySelector('#txtNombre').value;
            let intCodigo = document.querySelector('#txtCodigo').value;
            let strPrecio = document.querySelector('#txtPrecio').value;            
            let intStock = document.querySelector('#txtStock').value;
            let intStatus = document.querySelector('#listStatus').value;

            if(strNombre == '' || intCodigo == '' || strPrecio == '' || intStock == ''){
                swal("Atención", "Todos los campos son obligatorios.", "error");
                return false;
            }
            if(intCodigo.length < 5){
                swal("Atención", "El codigo debe ser mayor que 5 digitos.", "error");
                return false;
            }
            divLoading.style.display = "flex";
            tinyMCE.triggerSave();
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Uniformes/setUniforme';
            let formData = new FormData(formUniformes);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status){
                        swal("", objData.msg , "success");
                        document.querySelector("#idUniforme").value = objData.iduniforme;
                        document.querySelector("#containerGallery").classList.remove("notblock");

                        if(rowTable == ""){
                            tableUniformes.api().ajax.reload();
                        }else{
                            htmlStatus = intStatus == 1 ?
                            '<span class="badge badge-success">Activo</span>' :
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[1].innerHTML = intCodigo;
                            rowTable.cells[2].innerHTML = strNombre;
                            rowTable.cells[3].innerHTML = intStock;
                            rowTable.cells[4].innerHTML = smony+strPrecio;
                            rowTable.cells[5].innerHTML = htmlStatus;
                            rowTable = "";
                        }                   
                    }else{
                        swal("Error", objData.msg , "error")
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }

    if(document.querySelector(".btnAddImage")){
        let btnAddImage = document.querySelector(".btnAddImage");
        btnAddImage.onclick = function(e){
            let key = Date.now();
            let newElement = document.createElement("div");
            newElement.id= "div"+key;
            newElement.innerHTML = `
            <div class="prevImage"></div>
            <input type="file" name="foto" id="img${key}" class="inputUploadfile">
            <label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
            <button class="btnDeleteImage notblock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
        document.querySelector("#containerImages").appendChild(newElement);
        document.querySelector("#div"+key+" .btnUploadfile").click();
        fntInputFile();
        }
    }
    
}, false);

window.addEventListener('load', function() {
    fntModelos();
    fntInputFile();
}, false);

if(document.querySelector("#txtCodigo")){
    let inputCodigo = document.querySelector("#txtCodigo");
    inputCodigo.onkeyup = function() {
        if(inputCodigo.value.length >= 5){
            document.querySelector('#divBarCode').classList.remove("notblock");
            fntBarcode();
        }else{
            document.querySelector('#divBarCode').classList.add("notblock")
        }
    };
}

tinymce.init({
	selector: '#txtDescripcion',
	width: "100%",
    height: 400,    
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});

function fntInputFile(){
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    inputUploadfile.forEach(function(inputUploadfile){
        inputUploadfile.addEventListener('change', function(){
            let idUniforme = document.querySelector("#idUniforme").value;
            let parentId = this.parentNode.getAttribute("id");
            let idFile = this.getAttribute("id");
            let uploadFoto = document.querySelector("#"+idFile).value;
            let fileimg = document.querySelector("#"+idFile).files;
            let prevImg = document.querySelector("#"+parentId+" .prevImage");
            let nav = window.URL || window.webkitURL;
            if(uploadFoto != ''){
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                    prevImg.innerHTML = "Archivo no válido";
                    uploadFoto.value = "";
                    return false;
                }else{
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;

                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = base_url+'/Uniformes/setImage';
                    let formData = new FormData();
                    formData.append('iduniforme',idUniforme);
                    formData.append("foto", this.files[0]);
                    request.open("POST",ajaxUrl,true);
                    request.send(formData);
                    request.onreadystatechange = function(){
                        if(request.readyState != 4) return;
                        if(request.status == 200){
                            let objData = JSON.parse(request.responseText);
                            if(objData.status){
                                prevImg.innerHTML = `<img src="${objeto_url}">`;
                            document.querySelector("#"+parentId+" .btnDeleteImage").setAttribute("imgname",objData.imgname);
                            document.querySelector("#"+parentId+" .btnUploadfile").classList.add("notblock");
                            document.querySelector("#"+parentId+" .btnDeleteImage").classList.remove("notblock");
                            }else{
                                swal("Error", objData.msg , "error");
                            }                           
                        }
                    }
                }
            }
        });
    });
}

function fntDelItem(element){
    var nameImg = document.querySelector(element+' .btnDeleteImage').getAttribute("imgname");
    let idUniforme = document.querySelector("#idUniforme").value;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Uniformes/delFile';

    let formData = new FormData();
    formData.append('iduniforme',idUniforme);
    formData.append("file",nameImg);
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function(){
        if(request.readyState != 4) return;
        if(request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                let itemRemove = document.querySelector(element);
                itemRemove.parentNode.removeChild(itemRemove);
            }else{
                swal("", objData.msg , "error");
            }
        }
    }
}

function fntViewInfo(idUniforme){

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Uniformes/getUniforme/'+idUniforme;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status){

                let htmlImage = "";
                let objUniforme = objData.data;
                let estadoUniforme = objUniforme.status == 1 ?
                '<span class="badge badge-success">Activo</span>' :
                '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celCodigo").innerHTML = objUniforme.codigo;
                document.querySelector("#celNombre").innerHTML = objUniforme.nombre;
                document.querySelector("#celPrecio").innerHTML = objUniforme.precio;
                document.querySelector("#celStock").innerHTML = objUniforme.stock;
                document.querySelector("#celModelo").innerHTML = objUniforme.modelo;
                document.querySelector("#celStatus").innerHTML = estadoUniforme;
                document.querySelector("#celDescripcion").innerHTML = objUniforme.descripcion;

                if(objUniforme.images.length > 0){
                    let objUniformes = objUniforme.images;
                    for (let p = 0; p < objUniformes.length; p++) {
                        htmlImage +=`<img src="${objUniformes[p].url_image}"></img>`;
                    }
                }
                document.querySelector("#celFotos").innerHTML = htmlImage;
                $('#modalViewUniforme').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntEditInfo(element, idUniforme){
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML = "Actualizar Uniforme";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Uniformes/getUniforme/'+idUniforme;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status){
                
                let htmlImage = "";
                let objUniforme = objData.data;

                document.querySelector("#idUniforme").value = objUniforme.id_uniforme;
                document.querySelector("#txtNombre").value = objUniforme.nombre;
                document.querySelector("#txtDescripcion").value = objUniforme.descripcion;
                document.querySelector("#txtCodigo").value = objUniforme.codigo;
                document.querySelector("#txtPrecio").value = objUniforme.precio;
                document.querySelector("#txtStock").value = objUniforme.stock;
                document.querySelector("#listModelo").value = objUniforme.categoria_id;
                document.querySelector("#listStatus").value = objUniforme.status;
                tinymce.activeEditor.setContent(objUniforme.descripcion);
                $('#listModelo').selectpicker('render');
                $('#listStatus').selectpicker('render');
                fntBarcode();

                if(objUniforme.images.length > 0){
                    let objUniformes = objUniforme.images;
                    for (let p = 0; p < objUniformes.length; p++){
                        let key = Date.now()+p;
                        htmlImage +=`<div id="div${key}">
                        <div class="prevImage">
                        <img src="${objUniformes[p].url_image}"></img>
                        </div>
                        <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${objUniformes[p].img}">
                        <i class="fas fa-trash-alt"></i></button></div>`;
                    }
                }
                document.querySelector("#containerImages").innerHTML = htmlImage;
                document.querySelector("#divBarCode").classList.remove("notblock");
                document.querySelector("#containerGallery").classList.remove("notblock");                
                $('#modalFormUniformes').modal('show');
        
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelInfo(idUniforme){  
    swal({
        title: "Eliminar Uniforme",
        text: "¿Realmente quiere eliminar el Uniforme?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "No, Cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {

        if (isConfirm) 
            {
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url+'/Uniformes/delUniforme/';
                let strData = "idUniforme="+idUniforme;
                request.open("POST",ajaxUrl,true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(strData);
                request.onreadystatechange = function(){
                    if(request.readyState == 4 && request.status == 200){
                        let objData = JSON.parse(request.responseText);
                        if(objData.status)
                        {
                            swal("Eliminar!", objData.msg , "success");
                            tableUniformes.api().ajax.reload();
                        }else{
                            swal("Atención!", objData.msg , "error");
                        }
                    }
                }
            }
    });
}

function fntModelos(){
    if(document.querySelector('#listModelo')){
        let ajaxUrl = base_url+'/Modelos/getSelectModelos';
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                document.querySelector('#listModelo').innerHTML = request.responseText;
                $('#listModelo').selectpicker('render');
            }
        }
    }
}

function fntBarcode(){
    let codigo = document.querySelector("#txtCodigo").value;
    JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area){
    let elemntArea = document.querySelector(area);
    let vprint = window.open(' ', 'popimpr', 'height=400,width=600');
    vprint.document.write(elemntArea.innerHTML );
    vprint.document.close();
    vprint.print();
    vprint.close();
}

function openModal(){
    rowTable = "";
    document.querySelector('#idUniforme').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Uniforme";
    document.querySelector("#formUniformes").reset();
    document.querySelector("#divBarCode").classList.add("notblock");
    document.querySelector("#containerGallery").classList.add("notblock");
    document.querySelector("#containerImages").innerHTML = "";
    $('#modalFormUniformes').modal('show');
}