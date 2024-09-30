<?php
class Clientes extends Controllers{
		public function __construct(){
			//sessionStar();
			parent::__construct();
			session_start();
			session_regenerate_id(true);
			if(empty($_SESSION['login'])){
				header('location: '.base_url().'/login');
			}			
			getPermisos(3);
		}

		public function Clientes(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_tag'] = "Clientes";
			$data['page_title'] = "Clientes";
			$data['page_name'] = "clientes";
			$data['page_functions_js'] = "functions_clientes.js";
			$this->views->getView($this,"clientes",$data);
		}

		public function setCliente(){ 			
			
			if($_POST){				
				if(empty($_POST['txtNacionalidad'] || $_POST['txtCedula']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['txtRif']) || empty($_POST['txtNombreFiscal']) ||
				empty($_POST['txtDirFiscal']) ){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{ 
					$idUsuario = intval($_POST['idUsuario']);				
					$strNacionalidad = ucwords(strClean($_POST['txtNacionalidad']));
					$strCedula = intval($_POST['txtCedula']);
					$strNombre = ucwords(strClean($_POST['txtNombre']));
					$strApellido = ucwords(strClean($_POST['txtApellido']));
					$intTelefono = intval(strClean($_POST['txtTelefono']));
					$strEmail = strtolower(strClean($_POST['txtEmail']));
					$strRif = strClean($_POST['txtRif']);
					$strNomFiscal = strClean($_POST['txtNombreFiscal']);
					$strDirFiscal = strClean($_POST['txtDirFiscal']);
					$intTipoId = 4;
					$request_user = "";

					if($idUsuario == 0){
						$option = 1;
						$strPassword =  empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
						$strPasswordEncript = hash("SHA256",$strPassword);
						if($_SESSION['permisosMod']['w']){
						$request_user = $this->model->insertCliente($strNacionalidad, $strCedula, $strNombre, $strApellido, $intTelefono, $strEmail, $strPasswordEncript, $intTipoId, $strRif, $strNomFiscal, $strDirFiscal );
						}
					}else{
						$option = 2;
						$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
						if($_SESSION['permisosMod']['u']){
						$request_user = $this->model->updateCliente($idUsuario, $strNacionalidad, $strCedula, $strNombre, $strApellido, $intTelefono, $strEmail, $strPassword, $strRif, $strNomFiscal, $strDirFiscal);
						}
					}
					if($request_user > 0 ){
						if($option == 1){
							$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');

							$nombreUsuario = $strNombre.' '.$strApellido;

							$dataUsuario = array('nombreUsuario' => $nombreUsuario,
							 'email' => $strEmail,
							 'password' => $strPassword,
							 'asunto' => 'Bienvenido a tu tienda');
							sendEmail($dataUsuario,'email_bienvenida');

						}else{
							$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_user == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la cédula ya existe, ingrese otro.');
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}	
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getClientes(){
			if($_SESSION['permisosMod']['r']){
			$arrData = $this->model->selectClientes();			
			for ($i=0; $i < count($arrData); $i++) {
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				$arrData[$i]['identificacion'] = $arrData[$i]['nacionalidad']."-".$arrData[$i]['cedula'];

				if($_SESSION['permisosMod']['r']){
					$btnView = '<button class="btn btn-info btn-sm"  onClick="fntViewInfo('.$arrData[$i]['id_persona'].')" title="Ver Cliente"><i class="far fa-eye"></i></button>';
				}

				if($_SESSION['permisosMod']['u']){					
					$btnEdit = '<button class="btn btn-primary btn-sm"  onClick="fntEditInfo(this,'.$arrData[$i]['id_persona'].')" title="Editar Cliente"><i class="fa-solid fa-pencil"></i></button>';	
				}

				if($_SESSION['permisosMod']['d']){					
					$btnDelete = '<button class="btn btn-danger btn-sm"  onClick="fntDelInfo('.$arrData[$i]['id_persona'].')" title="Eliminar Cliente"><i class="fa-solid fa-trash"></i></button>';			
				}

				$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
			}			
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
			die();
		}

		public function getCliente($idpersona){
			if($_SESSION['permisosMod']['r']){
			$idusuario = intval($idpersona);
			if($idusuario > 0){
				$arrData = $this->model->selectCliente($idusuario);				
				if(empty($arrData)){
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}else{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function delCliente(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
				$intIdpersona = intval($_POST['idUsuario']);
				$requestDelete = $this->model->deleteCliente($intIdpersona);
				if($requestDelete){
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el cliente.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar al cliente.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}	
			}
			die();
		}
}
?>