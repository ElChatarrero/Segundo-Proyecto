let tableClientes;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    tableClientes = $('#tableClientes').dataTable( {
		"aProcessing":true,
		"aServerSide":true,
        "language": {
        	"url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json"
        },
        "ajax":{
            "url": " "+base_url+"/Clientes/getClientes",
            "dataSrc":""
        },
        "columns":[
            {"data":"id_persona"},
            {"data":"identificacion"},
            {"data":"nombre"},
            {"data":"apellido"},
            {"data":"email_user"},
            {"data":"telefono"},           
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

    if(document.querySelector("#formCliente")){
        let formCliente = document.querySelector("#formCliente");
        formCliente.onsubmit = function(e) {
        e.preventDefault();
        let strNacionalidad = document.querySelector('#txtNacionalidad').value;
        let strCedula = document.querySelector('#txtCedula').value;
        let strNombre = document.querySelector('#txtNombre').value;
        let strApellido = document.querySelector('#txtApellido').value;
        let strEmail = document.querySelector('#txtEmail').value;
        let intTelefono = document.querySelector('#txtTelefono').value;
        let strPassword = document.querySelector('#txtPassword').value;
        let strRif = document.querySelector('#txtRif').value;        
        let strNomFiscal = document.querySelector('#txtNombreFiscal').value;
        let strDirFiscal = document.querySelector('#txtDirFiscal').value;

        if(strNacionalidad == '' || strCedula == '' || strApellido == '' || strNombre == '' || strEmail == '' || intTelefono == '' || strRif == '' || strNomFiscal == '' || strDirFiscal == '')
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
            let ajaxUrl = base_url+'/Clientes/setCliente';
            let formData = new FormData(formCliente);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        if(rowTable == ""){
                            tableClientes.api().ajax.reload();
                        }else{
                            identificacion = strNacionalidad+'-'+strCedula;
                            rowTable.cells[1].textContent = identificacion;
                            rowTable.cells[2].textContent = strNombre;
                            rowTable.cells[3].textContent = strApellido;
                            rowTable.cells[4].textContent = strEmail;
                            rowTable.cells[5].textContent = intTelefono;   
                            rowTable = "";                     
                        }
                        $('#modalFormCliente').modal("hide");
                        formCliente.reset();
                        swal("Cliente", objData.msg, "success");
                        
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

function fntViewInfo(idPersona){      

   let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Clientes/getCliente/'+idPersona;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {                
                document.querySelector("#celCedula").innerHTML = objData.data.nacionalidad+'-'+objData.data.cedula;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celApellido").innerHTML = objData.data.apellido;
                document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                document.querySelector("#celEmail").innerHTML = objData.data.email_user;
                document.querySelector("#celRif").innerHTML = objData.data.rif;
                document.querySelector("#celNomFiscal").innerHTML = objData.data.nombre_fiscal;
                document.querySelector("#celDirFiscal").innerHTML = objData.data.direccion_fiscal;
                document.querySelector("#celFechaRegistro").innerHTML = objData.data.fecharegistro;
                 $('#modalViewCliente').modal('show');  
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntEditInfo(element, idPersona){
    rowTable = element.parentNode.parentNode.parentNode;
     document.querySelector('#titleModal').innerHTML ="Actualizar Cliente";
     document.querySelector('.modal-header').classList.replace("headerRegister" , "headerUpdate");
     document.querySelector('#btnActionForm').classList.replace("btn-primary" , "btn-info");
     document.querySelector('#btnText').innerHTML ="Actualizar";           

    
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Clientes/getCliente/'+idPersona;
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
                    document.querySelector('#txtRif').value = objData.data.rif;
                    document.querySelector('#txtNombreFiscal').value = objData.data.nombre_fiscal;
                    document.querySelector('#txtDirFiscal').value = objData.data.direccion_fiscal;
                    $('#txtNacionalidad').selectpicker('render');
                }
        }
        $('#modalFormCliente').modal('show');
    }         
}

function fntDelInfo(idPersona){  
            
    swal({
        title: "Eliminar Cliente",
        text: "¿Realmente quiere eliminar al Cliente?",
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
                let ajaxUrl = base_url+'/Clientes/delCliente/';
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
                            tableClientes.api().ajax.reload();
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
    document.querySelector('#titleModal').innerHTML = "Nuevo Cliente";
    document.querySelector('#msj_existe_persona').innerHTML ="";
    document.querySelector('#msj_existe_email').innerHTML ="";    
    document.querySelector("#formCliente").reset();
    $('#modalFormCliente').modal('show');
}