
var tableRoles;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

	tableRoles = $('#tableRoles').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
        },
        "ajax":{
            "url": " "+base_url+"/Roles/getRoles",
            "dataSrc":""
        },
        "columns":[
            {"data":"id_rol"},
            {"data":"nombre_rol"},
            {"data":"descripcion"},
            {"data":"status"},
            {"data":"options"}
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });
    
    var formRol = document.querySelector("#formRol");
    formRol.onsubmit = function(e){
        e.preventDefault();

        var intIdRol = document.querySelector('#idRol').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strDescripcion = document.querySelector('#txtDescripcion').value;
        var instStatus = document.querySelector('#listStatus').value;
        if(strNombre == '' || strDescripcion == '' || instStatus == '')
        {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        }
        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Roles/setRol';
        var formData = new FormData(formRol);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                 
                 var objData = JSON.parse(request.responseText);

                 if(objData.status)
                 {
                     $('#modalFormRol').modal("hide");
                     formRol.reset();
                     swal("Roles de usuario", objData.msg ,"success");
                     tableRoles.api().ajax.reload();
                 }else{
                     swal("Error", objData.msg , "error");
                 }              
             }
             divLoading.style.display = "none";
            return false;
        }
    }
});

$('#tableRoles').DataTable();

function openModal(){
    document.querySelector('#idRol').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    document.querySelector("#formRol").reset();
    $('#modalFormRol').modal('show');
}

window.addEventListener('load', function() {
    /*fntEditRol();
    fntDelRol();
    fntPermisos();*/
}, false);

function fntEditRol(idRol){    

            document.querySelector('#titleModal').innerHTML = "Actualizar Rol";
            document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
            document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
            document.querySelector('#btnText').innerHTML = "Actualizar";

            var idrol = idRol;
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Roles/getRol/'+idrol;
            request.open("GET",ajaxUrl, true);
            request.send();

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){

                    var objData = JSON.parse(request.responseText);

                    if(objData.status)
                    {
                        document.querySelector("#idRol").value = objData.data.id_rol;
                        document.querySelector("#txtNombre").value = objData.data.nombre_rol;
                        document.querySelector("#txtDescripcion").value = objData.data.descripcion;

                        if(objData.data.status == 1)
                        {
                            var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
                        }else{
                            var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
                        }

                        var htmlSelect = `${optionSelect}
                                  <option value="1">Activo</option>
                                  <option value="2">Inactivo</option>
                                `;
                        document.querySelector("#listStatus").innerHTML = htmlSelect;
                        $('#modalFormRol').modal('show');
                    }else{
                        swal("Error", objData.msg , "error"); 

                    }
                }
            }
}

function fntDelRol(idRol){
   
            var idrol = idRol;
            
            swal({
                title: "Eliminar Rol",
                text: "¿Realmente quiere eliminar el Rol?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, Eliminar!",
                cancelButtonText: "No, Cancelar!",                
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm) {

                if (isConfirm) 
                    {
                        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                        var ajaxUrl = base_url+'/Roles/delRol/';
                        var strData = "idrol="+idrol;
                        request.open("POST",ajaxUrl,true);
                        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        request.send(strData);
                        request.onreadystatechange = function(){
                            if(request.readyState == 4 && request.status == 200){
                                var objData = JSON.parse(request.responseText);
                                if(objData.status)
                                {
                                    swal("Eliminar!", objData.msg , "success");
                                    tableRoles.api().ajax.reload(function(){
                                        fntEditRol();
                                        fntDelRol();
                                        fntPermisos();
                                    });
                                }else{
                                    swal("Atención!", objData.msg , "error");
                                }
                            }
                        }
                    }

            });
}

function fntPermisos(idRol){
    
            var idrol = idRol;
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Permisos/getPermisosRol/'+idrol;
            request.open("GET",ajaxUrl,true);
            request.send();

            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){                    
                    document.querySelector('#contentAjax').innerHTML = request.responseText;
                    $('.modalPermisos').modal('show');
                    document.querySelector('#formPermisos').addEventListener('submit',fntSavePermisos,false);
                }
            }
}

function fntSavePermisos(evnet){
    evnet.preventDefault();
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Permisos/setPermisos';
    var formElement = document.querySelector("#formPermisos");
    var formData = new FormData(formElement);
    request.open("POST",ajaxUrl,true);
    request.send(formData);

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                swal("Permisos de usuario", objData.msg, "success");
            }else{
                swal("Error", objData.msg, "error");
            }
        }
    }

}