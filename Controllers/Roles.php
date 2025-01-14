<?php 
	class Roles extends Controllers{
		public function __construct(){
			//sessionStar();
			parent::__construct();
			session_start();
			session_regenerate_id(true);
			if(empty($_SESSION['login'])){
				header('location: '.base_url().'/login');
			}			
			getPermisos(2);
		}

		public function Roles(){
			if(empty($_SESSION['permisosMod']['r'])){
				header("Location:".base_url().'/dashboard');
			}
			$data['page_id'] = 3;
			$data['page_tag'] = "Roles de Usuario";
			$data['page_name'] = "roles";
			$data['page_title'] = "Roles de Usuario";
            $data['page_functions_js'] = "functions_roles.js";
			$this->views->getView($this,"roles",$data);
		}

		//Listado de Roles
		public function getRoles(){
			if($_SESSION['permisosMod']['r']){
			$btnView = '';
			$btnEdit = '';
			$btnDelete = '';
			$arrData = $this->model->selectRoles();	
			for ($i=0; $i < count($arrData); $i++){
				

				if($arrData[$i]['status'] == 1){
					$arrData[$i]['status'] =' <span class="badge badge-success">Activo</span>';
				}else{
					$arrData[$i]['status'] =' <span class="badge badge-danger">Inactivo</span> ';
				}				

				if($_SESSION['permisosMod']['u']){
					$btnView = '<button class="btn btn-secondary btn-sm btnPermisosRol"  onClick="fntPermisos('.$arrData[$i]['id_rol'].')" title="Permisos"><i class="fa-solid fa-key"></i></button>';
					$btnEdit = '<button class="btn btn-primary btn-sm btnEditRol" onClick="fntEditRol('.$arrData[$i]['id_rol'].')" title="Editar"><i class="fa-solid fa-pencil"></i></button>';
				}

				if($_SESSION['permisosMod']['d']){
					$btnDelete = '<button class="btn btn-danger btn-sm btnDelRol"  onClick="fntDelRol('.$arrData[$i]['id_rol'].')" title="Eliminar"><i class="fa-solid fa-trash"></i></button>';
				}
				$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
			}
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function getSelectRoles(){
			$htmlOptions = "";
			$arrData = $this->model->selectRoles();
			if(count($arrData) > 0){
				for($i=0; $i < count($arrData); $i++) {
					if($arrData[$i]['status'] == 1){
						$htmlOptions .= '<option value="'.$arrData[$i]['id_rol'].'">'.$arrData[$i]['nombre_rol'].'</option>';
					}					
				}
			}
			echo $htmlOptions;
			die();
		}

		//Extraer datos de un rol
		public function getRol($idrol){
			if($_SESSION['permisosMod']['r']){
			$intIdrol = intval(strClean($idrol));
			if($intIdrol > 0){
				$arrData = $this->model->selectRol($intIdrol);
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

		//Envio de datos del formulario
		public function setRol(){
			$intIdrol = intval($_POST['idRol']);
			$strRol = strClean($_POST['txtNombre']);
			$strDescripcion = strClean($_POST['txtDescripcion']);
			$intStatus = intval($_POST['listStatus']);
			$request_rol = "";

			if($intIdrol == 0){
				//Crear
				if($_SESSION['permisosMod']['w']){
				$request_rol = $this->model->insertRol($strRol, $strDescripcion, $intStatus);
				$option = 1;
				}
			}else{
				//Actualizar
				if($_SESSION['permisosMod']['u']){
				$request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescripcion, $intStatus);
				$option = 2;
				}
			}
			if($request_rol > 0 ){
				if($option == 1){
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados Correctamente.');
				}else{
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
				}

			}else if($request_rol == 'exist'){
				$arrResponse = array('status' => false, 'msg' => '¡Atención! El Rol ya Existe.');
			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los Datos.');
			}			
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			die();
		}

		

		public function delRol(){
			if($_POST){	
				if($_SESSION['permisosMod']['d']){		
					$intIdrol = intval($_POST['idrol']);
					$requestDelete = $this->model->deleteRol($intIdrol);
					if($requestDelete == 'ok'){
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
					}else if($requestDelete == 'exist'){
						$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);	
				}	
			}
				die();
		}

	}
 ?>