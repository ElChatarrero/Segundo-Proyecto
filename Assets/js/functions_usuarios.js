let tableUsuarios;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){

    tableUsuarios = $('#tableUsuarios').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
        },
        "ajax":{
            "url": " "+base_url+"/Usuarios/getUsuarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"id_persona"},
            {"data":"nombre"},
            {"data":"apellido"},
            {"data":"email_user"},
            {"data":"telefono"},
            {"data":"nombre_rol"},
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
                    "columns": [0, 1, 2, 3, 4, 5, 6]
                }
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"Exportar a Excel",
                "className": "btn btn-success",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5, 6]
                }
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Exportar a PDF",
                "className": "btn btn-danger",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5, 6]
                }
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Exportar a CSV",
                "className": "btn btn-info",
                "exportOptions":{
                    "columns": [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    if(document.querySelector("#formUsuario")){
        let formUsuario = document.querySelector("#formUsuario");
        formUsuario.onsubmit = function(e) {
        e.preventDefault();
        let strNacionalidad = document.querySelector('#txtNacionalidad').value;
        let strCedula = document.querySelector('#txtCedula').value;
        let strNombre = document.querySelector('#txtNombre').value;
        let strApellido = document.querySelector('#txtApellido').value;
        let strEmail = document.querySelector('#txtEmail').value;
        let intTelefono = document.querySelector('#txtTelefono').value;
        let intTipousuario = document.querySelector('#listRolid').value;
        let strPassword = document.querySelector('#txtPassword').value;
        let intStatus = document.querySelector('#listStatus').value;

        if(strNacionalidad == '' || strCedula == '' || strApellido == '' || strNombre == '' || strEmail == '' || intTelefono == '' || intTipousuario == '')
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0; i < elementsValid.length; i++) { 
                if(elementsValid[i].classList.contains('is-invalid')) { 
                    swal("Atención", "Por favor verifique los campos en rojo." , "error");
                    return false;
                }
            }

            divLoading.style.display = "flex"; 
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Usuarios/setUsuario';
            let formData = new FormData(formUsuario);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        if(rowTable == ""){
                            tableUsuarios.api().ajax.reload();
                        }else{
                            htmlStatus = intStatus == 1 ? 
                            '<span class="badge badge-success">Activo</span>' : 
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[1].innerHTML = strNombre;
                            rowTable.cells[2].innerHTML = strApellido;
                            rowTable.cells[3].innerHTML = strEmail;
                            rowTable.cells[4].innerHTML = intTelefono;
                            rowTable.cells[5].innerHTML = document.querySelector("#listRolid").selectedOptions[0].text;
                            rowTable.cells[6].innerHTML = htmlStatus;
                        }
                        $('#modalFormUsuario').modal("hide");
                        formUsuario.reset();
                        swal("Usuarios", objData.msg, "success");
                        
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }

        }
    }    
}, false);


window.addEventListener('load', function(){
    fntRolesUsuario();
    /*fntViewUsuario();
    fntEditUsuario();
    fntDelUsuario();*/
}, false);

function fntRolesUsuario(){
    if(document.querySelector('#listRolid')){

    let ajaxUrl = base_url+'/Roles/getSelectRoles';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            document.querySelector('#listRolid').innerHTML = request.responseText;
            //document.querySelector('#listRolid').value = 1;
            $('#listRolid').selectpicker('render');           
            }
        } 
    }   
}

function fntViewUsuario(idPersona){      
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Usuarios/getUsuario/'+idPersona;
            request.open("GET",ajaxUrl,true);
            request.send();
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        let estadoUsuario = objData.data.status == 1 ?
                        '<span class="badge badge-success">Activo</span>':
                        '<span class="badge badge-danger">Inactivo</span>';
                       
                        document.querySelector("#celCedula").innerHTML = objData.data.nacionalidad+'-'+objData.data.cedula;
                        document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                        document.querySelector("#celApellido").innerHTML = objData.data.apellido;
                        document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                        document.querySelector("#celEmail").innerHTML = objData.data.email_user;
                        document.querySelector("#celTipoUsuario").innerHTML = objData.data.nombre_rol;
                        document.querySelector("#celEstado").innerHTML = estadoUsuario;
                        document.querySelector("#celFechaRegistro").innerHTML = objData.data.fecharegistro;
                         $('#modalViewUser').modal('show');
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
            }  
}
function fntEditUsuario(element, idPersona){
            rowTable = element.parentNode.parentNode.parentNode;
             document.querySelector('#titleModal').innerHTML ="Actualizar Usuario";
             document.querySelector('.modal-header').classList.replace("headerRegister" , "headerUpdate");
             document.querySelector('#btnActionForm').classList.replace("btn-primary" , "btn-info");
             document.querySelector('#btnText').innerHTML ="Actualizar";           

            
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Usuarios/getUsuario/'+idPersona;
            request.open("GET",ajaxUrl,true);
            request.send();
            request.onreadystatechange = function(){

                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                        {
                            document.querySelector("#idUsuario").value = objData.data.id_persona;
                            document.querySelector("#txtNacionalidad").value = objData.data.nacionalidad;
                            document.querySelector("#txtCedula").value = objData.data.cedula;
                            document.querySelector("#txtNombre").value = objData.data.nombre;
                            document.querySelector("#txtApellido").value = objData.data.apellido;
                            document.querySelector("#txtTelefono").value = objData.data.telefono;
                            document.querySelector("#txtEmail").value = objData.data.email_user;
                            document.querySelector("#listRolid").value = objData.data.id_rol;
                            $('#listRolid').selectpicker('render');
                            $('#txtNacionalidad').selectpicker('render');
            
                            if(objData.data.status == 1){
                                document.querySelector("#listStatus").value = 1;
                            }else{
                                document.querySelector("#listStatus").value = 2;
                            }
                            $('#listStatus').selectpicker('render');
                        }
                }
                $('#modalFormUsuario').modal('show');
            }         
}
function fntDelUsuario(idPersona){
  
            
            swal({
                title: "Eliminar Usuario",
                text: "¿Realmente quiere eliminar el Usuario?",
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
                        let ajaxUrl = base_url+'/Usuarios/delUsuario/';
                        let strData = "idUsuario="+idPersona;
                        request.open("POST",ajaxUrl,true);
                        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        request.send(strData);
                        request.onreadystatechange = function(){
                            if(request.readyState == 4 && request.status == 200){
                                let objData = JSON.parse(request.responseText);
                                if(objData.status)
                                {
                                    swal("Eliminar!", objData.msg , "success");
                                    tableUsuarios.api().ajax.reload();
                                }else{
                                    swal("Atención!", objData.msg , "error");
                                }
                            }
                        }
                    }

            });
}

function buscarDatos(nac,cedula){
    //Instanciamos el objecto HMLHttpRequest
    ajax = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    ajaxUrl = base_url+'/Usuarios/valiUsu';
    //Abrimos la conexion script
    ajax.open("POST", ajaxUrl, false);
    //Indicamos que funcion vigila el cambio de estado
    ajax.onreadystatechange=function(){
        if (ajax.readyState == 4){
            //Mostramos resultado
            let resultado = ajax.responseText;
            ResC(resultado);
        }
    } 
    //Enviamos algo para que funcione el proceso
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("txtNacionalidad="+nac+"&txtCedula="+cedula);     
}
function ResC(resultado){
    document.getElementById('msj_existe_persona').innerHTML = '';
    if(resultado==0){
        document.getElementById('msj_existe_persona').innerHTML = 'Disponible';
    }else{
        document.getElementById('msj_existe_persona').innerHTML = "Cédula ya Registrada";
        document.getElementById('txtCedula').value = "";
    }
}

function buscarEmail(email){
    //Instanciamos el objecto HMLHttpRequest
    ajax = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    ajaxUrl = base_url+'/Usuarios/valiEmail';
    //Abrimos la conexion script
    ajax.open("POST", ajaxUrl, false);
    //Indicamos que funcion vigila el cambio de estado
    ajax.onreadystatechange=function(){
        if (ajax.readyState == 4){
            //Mostramos resultado
            let Resultado = ajax.responseText;
            Res(Resultado);
        }
    } 
    //Enviamos algo para que funcione el proceso
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("txtEmail="+email);     
}
function Res(Resultado){
    document.getElementById('msj_existe_email').innerHTML = '';
    if(Resultado==0){
        document.getElementById('msj_existe_email').innerHTML = 'Disponible';
    }else{
        document.getElementById('msj_existe_email').innerHTML = "Email ya Registrado";
        document.getElementById('txtEmail').value = "";
    }
}

function openModal(){
    rowTable = "";
    document.querySelector('#idUsuario').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
    document.querySelector('#msj_existe_persona').innerHTML ="";
    document.querySelector('#msj_existe_email').innerHTML ="";    
    document.querySelector("#formUsuario").reset();
    $('#modalFormUsuario').modal('show');
}