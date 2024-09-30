function openModalPerfil(){
    $('#modalFormPerfil').modal('show');
}
let divLoading = document.querySelector("#divLoading");
//Actualizar Perfil
if(document.querySelector("#formPerfil")){
    let formPerfil = document.querySelector("#formPerfil");
    let base_url = 'http://localhost/tienda_virtual';
    formPerfil.onsubmit = function(e){
        e.preventDefault();
        let strNacionalidad = document.querySelector('#txtNacionalidad').value;
        let strCedula = document.querySelector('#txtCedula').value;
        let strNombre = document.querySelector('#txtNombre').value;
        let strApellido = document.querySelector('#txtApellido').value;
        let intTelefono = document.querySelector('#txtTelefono').value;
        let strPassword = document.querySelector('#txtPassword').value;
        let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;

        if(strNacionalidad == '' || strCedula == '' || strApellido == '' || strNombre == '' || intTelefono == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }

        if(strPassword != "" || strPasswordConfirm != ""){

            if(strPassword != strPasswordConfirm){
                swal("Atención", "las contraseñas no son iguales." , "info");
                return false;
            }
            if(strPassword.length < 6 ){
                swal("Atención", "La contraseña debe tener mínimo 6 caracteres.", "info");
                return false;
            }
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
        let ajaxUrl = base_url+'/Usuarios/putPerfil';
        let formData = new FormData(formPerfil);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormPerfil').modal("hide");
                    swal({
                        title: "",
                        text: objData.msg,
                        type: "success",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: false,
                    }, function(isConfirm){
                        if(isConfirm){
                            location.reload();
                        }
                    });   
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
            return false;
        }
    }
}
//Actualizar Datos Fiscales
if(document.querySelector("#formDataFiscal")){
    let formDataFiscal = document.querySelector("#formDataFiscal");
    let base_url = 'http://localhost/tienda_virtual';
    formDataFiscal.onsubmit = function(e){
        e.preventDefault();
        let strRif = document.querySelector('#txtRif').value;
        let strNombreFiscal = document.querySelector('#txtNombreFiscal').value;
        let strDirFiscal = document.querySelector('#txtDirFiscal').value;
        

        if(strRif == '' || strNombreFiscal == '' || strDirFiscal == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }
       
        divLoading.style.display = "flex"; 
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Usuarios/putDFiscal';
        let formData = new FormData(formDataFiscal);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        request.onreadystatechange = function(){
            if(request.readyState != 4) return;
            if(request.status == 200){
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormPerfil').modal("hide");
                    swal({
                        title: "",
                        text: objData.msg,
                        type: "success",
                        confirmButtonText: "Aceptar",
                        closeOnConfirm: false,
                    }, function(isConfirm){
                        if(isConfirm){
                            location.reload();
                        }
                    });   
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
            return false;
        }
    }
}