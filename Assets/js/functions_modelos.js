
let rowTable = "";
let tableModelos;

document.addEventListener('DOMContentLoaded',function(){

    tableModelos = $('#tableModelos').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
        },
        "ajax":{
            "url": " "+base_url+"/Modelos/getModelos",
            "dataSrc":""
        },
        "columns":[
            {"data":"id_categoria"},
            {"data":"nombre"},
            {"data":"descripcion"},
            {"data":"status"},           
            {"data":"options"}
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary",
                "exportOptions":{
                    "columns": [0, 1, 2, 3]
                }
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"Exportar a Excel",
                "className": "btn btn-success",
                "exportOptions":{
                    "columns": [0, 1, 2, 3]
                }
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Exportar a PDF",
                "className": "btn btn-danger",
                "exportOptions":{
                    "columns": [0, 1, 2, 3]
                }
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Exportar a CSV",
                "className": "btn btn-info",
                "exportOptions":{
                    "columns": [0, 1, 2, 3]
                }
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    if(document.querySelector("#foto")){
        let foto = document.querySelector("#foto");
        foto.onchange = function(e) {
            let uploadFoto = document.querySelector("#foto").value;
            let fileimg = document.querySelector("#foto").files;
            let nav = window.URL || window.webkitURL;
            let contactAlert = document.querySelector('#form_alert');
            if(uploadFoto !=''){
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                    if(document.querySelector('#img')){
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.add("notBlock");
                    foto.value="";
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        if(document.querySelector('#img')){
                            document.querySelector('#img').remove();
                        }
                        document.querySelector('.delPhoto').classList.remove("notBlock");
                        let objeto_url = nav.createObjectURL(this.files[0]);
                        document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src="+objeto_url+">";
                    }
            }else{
                alert("No selecciono foto");
                if(document.querySelector('#img')){
                    document.querySelector('#img').remove();
                }
            }
        }
    }
    
    if(document.querySelector(".delPhoto")){
        let delPhoto = document.querySelector(".delPhoto");
        delPhoto.onclick = function(e) {
            document.querySelector("#foto_remove").value = 1;
            removePhoto();
        }
    }

    //Nuevo modelo
    let formModelo = document.querySelector("#formModelo");
    formModelo.onsubmit = function(e){
        e.preventDefault();

        let strNombre = document.querySelector('#txtNombre').value;
        let strDescripcion = document.querySelector('#txtDescripcion').value;
        let instStatus = document.querySelector('#listStatus').value;
        if(strNombre == '' || strDescripcion == '' || instStatus == '')
        {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        }
        divLoading.style.display = "flex";
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Modelos/setModelo';
        let formData = new FormData(formModelo);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                 
                 let objData = JSON.parse(request.responseText);

                 if(objData.status)
                 {
                    if(rowTable == ""){
                        tableModelos.api().ajax.reload();
                    }else{

                        htmlStatus = instStatus == 1 ?
                        '<span class="badge badge-success">Activo</span>' :
                        '<span class="badge badge-danger">Inactivo</span>';

                        rowTable.cells[1].innerHTML = strNombre;
                        rowTable.cells[2].innerHTML = strDescripcion;
                        rowTable.cells[3].innerHTML = htmlStatus;
                        rowTable = "";
                    }
                     $('#modalFormModelos').modal("hide");
                     formModelo.reset();
                     swal("Modelo", objData.msg ,"success");
                     removePhoto();
                     
                 }else{
                     swal("Error", objData.msg , "error");
                 }              
             }
             divLoading.style.display = "none";
            return false;
        }
    }
},false);

function fntViewInfo(idModelo){      

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
     let ajaxUrl = base_url+'/Modelos/getModelo/'+idModelo;
     request.open("GET",ajaxUrl,true);
     request.send();
     request.onreadystatechange = function(){
         if(request.readyState == 4 && request.status == 200){
             let objData = JSON.parse(request.responseText);
             if(objData.status)
             {
                let estado = objData.data.status == 1 ?
                '<span class="badge badge-success">Activo</span>' :
                '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celId").innerHTML = objData.data.id_categoria;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celDescripcion").innerHTML = objData.data.descripcion;
                document.querySelector("#celEstado").innerHTML = estado;
                document.querySelector("#imgModelo").innerHTML = '<img src="'+objData.data.url_portada+'"></img>';

                 
                  $('#modalViewModelo').modal('show');  
             }else{
                 swal("Error", objData.msg , "error");
             }
         }
     }
 }

 function fntEditInfo(element,idModelo){
    
    rowTable = element.parentNode.parentNode.parentNode;
     document.querySelector('#titleModal').innerHTML ="Actualizar Modelo";
     document.querySelector('.modal-header').classList.replace("headerRegister" , "headerUpdate");
     document.querySelector('#btnActionForm').classList.replace("btn-primary" , "btn-info");
     document.querySelector('#btnText').innerHTML ="Actualizar";   

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
     let ajaxUrl = base_url+'/Modelos/getModelo/'+idModelo;
     request.open("GET",ajaxUrl,true);
     request.send();
     request.onreadystatechange = function(){
         if(request.readyState == 4 && request.status == 200){
             let objData = JSON.parse(request.responseText);
             if(objData.status)
             {

                document.querySelector("#idModelo").value = objData.data.id_categoria;
                document.querySelector("#txtNombre").value = objData.data.nombre;
                document.querySelector("#txtDescripcion").value = objData.data.descripcion;
                document.querySelector("#foto_actual").value = objData.data.portada;
                document.querySelector("#foto_remove").value = 0;

                if(objData.data.status == 1){
                    document.querySelector("#listStatus").value = 1;
                }else{
                    document.querySelector("#listStatus").value = 2;
                }
                $('#listStatus').selectpicker('render');
                
                if(document.querySelector('#img')){
                    document.querySelector('#img').src = objData.data.url_portada;
                }else{
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src="+objData.data.url_portada+">";
                }

                if(objData.data.portada == 'portada_categoria.png'){
                    document.querySelector('.delPhoto').classList.add("notBlock");
                }else{
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                }

                $('#modalFormModelos').modal('show');
             }else{
                 swal("Error", objData.msg , "error");
             }
         }
     }
 }

 function fntDelInfo(idModelo){  
            
    swal({
        title: "Eliminar Modelo",
        text: "¿Realmente quiere eliminar el Modelo?",
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
                let ajaxUrl = base_url+'/Modelos/delModelo/';
                let strData = "idModelo="+idModelo;
                request.open("POST",ajaxUrl,true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(strData);
                request.onreadystatechange = function(){
                    if(request.readyState == 4 && request.status == 200){
                        let objData = JSON.parse(request.responseText);
                        if(objData.status)
                        {
                            swal("Eliminar!", objData.msg , "success");
                            tableModelos.api().ajax.reload();
                        }else{
                            swal("Atención!", objData.msg , "error");
                        }
                    }
                }
            }
    });
}

function removePhoto(){
    document.querySelector('#foto').value ="";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if(document.querySelector('#img')){
        document.querySelector('#img').remove();
    }    
}

function openModal(){
    rowTable = "";
    document.querySelector('#idModelo').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Modelo"; 
    document.querySelector("#formModelo").reset();
    $('#modalFormModelos').modal('show');
    removePhoto();
}