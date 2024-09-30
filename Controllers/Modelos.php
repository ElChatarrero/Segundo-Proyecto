<?php
class Modelos extends Controllers{
    public function __construct(){
        //sessionStar();
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if(empty($_SESSION['login'])){
            header('location: '.base_url().'/login');
        }			
        getPermisos(6);
    }

    public function Modelos(){
        if(empty($_SESSION['permisosMod']['r'])){
            header("Location:".base_url().'/dashboard');
        }
        $data['page_tag'] = "Modelos";
        $data['page_title'] = "Modelos";
        $data['page_name'] = "modelos";
        $data['page_functions_js'] = "functions_modelos.js";
        $this->views->getView($this,"modelos",$data);
    }

    //Envio de datos del formulario
	public function setModelo(){

         if($_POST){

            if(empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['listStatus']) ){
                $arrResponse = array("status" => false, "msg" => 'Datos incorrectos');
            }else{
                $intIdModelo = intval($_POST['idModelo']);
                $strModelo = strClean($_POST['txtNombre']);
                $strDescripcion = strClean($_POST['txtDescripcion']);
                $intStatus = intval($_POST['listStatus']);

                $ruta = strtolower(clear_cadena($strModelo));
                $ruta = str_replace(" ","-",$ruta);


                $foto = $_FILES['foto'];
                $nombre_foto = $foto['name'];
                $type = $foto['type'];
                $url_temp = $foto['tmp_name'];                    
                $imgPortada = 'portada_categoria.png';
                $request_modelo = "";

                if($nombre_foto != ''){
                    $imgPortada = 'img_'.md5(date('d-m-Y H:m:s')).'.jpg';
                    }

                if($intIdModelo == 0){
				    //Crear
				    if($_SESSION['permisosMod']['w']){
				    $request_modelo = $this->model->insertModelo($strModelo, $strDescripcion, $imgPortada, $ruta, $intStatus);
				    $option = 1;
                    }
			    }else{
				    //Actualizar
				    if($_SESSION['permisosMod']['u']){
                        if($nombre_foto == ''){
                            if($_POST['foto_actual'] != 'portada_categoria.png' && $_POST['foto_remove'] == 0){
                                $imgPortada = $_POST['foto_actual'];
                            }
                        }
				    $request_modelo = $this->model->updateModelo($intIdModelo, $strModelo, $strDescripcion, $imgPortada, $ruta, $intStatus);
				    $option = 2;
				    }
			    }
            
            if($request_modelo > 0 ){
				if($option == 1){
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados Correctamente.');
                    if($nombre_foto != ''){ uploadImage($foto,$imgPortada);}
				}else{
					$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
                    if($nombre_foto != ''){ uploadImage($foto,$imgPortada);}

                    if(($nombre_foto == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'portada_categoria.png') || ($nombre_foto != '' && $_POST['foto_actual'] != 'portada_categoria.png')){
                        deleteFile($_POST['foto_actual']);
                    }
				}

			}else if($request_modelo == 'exist'){
				$arrResponse = array('status' => false, 'msg' => '¡Atención! El Modelo ya Existe.');
			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los Datos.');
			}

            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }	
			die();
	}

    public function getModelos(){
        if($_SESSION['permisosMod']['r']){
        $arrData = $this->model->selectModelos();		

        for ($i=0; $i < count($arrData); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';

            if($arrData[$i]['status'] == 1){
                $arrData[$i]['status'] =' <span class="badge badge-success">Activo</span>';
                }else{
                $arrData[$i]['status'] =' <span class="badge badge-danger">Inactivo</span> ';
                }


                if($_SESSION['permisosMod']['r']){
                $btnView = '<button class="btn btn-info btn-sm"  onClick="fntViewInfo('.$arrData[$i]['id_categoria'].')" title="Ver Modelo"><i class="far fa-eye"></i></button>';
                }

                if($_SESSION['permisosMod']['u']){					
                $btnEdit = '<button class="btn btn-primary btn-sm"  onClick="fntEditInfo(this,'.$arrData[$i]['id_categoria'].')" title="Editar Modelo"><i class="fa-solid fa-pencil"></i></button>';	
                }

                if($_SESSION['permisosMod']['d']){					
                $btnDelete = '<button class="btn btn-danger btn-sm"  onClick="fntDelInfo('.$arrData[$i]['id_categoria'].')" title="Eliminar Modelo"><i class="fa-solid fa-trash"></i></button>';			
                }

                $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
            }			
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getModelo($idmodelo){
        if($_SESSION['permisosMod']['r']){
        $intIdModelo = intval(strClean($idmodelo));
        if($intIdModelo > 0){
            $arrData = $this->model->selectModelo($intIdModelo);            
            if(empty($arrData)){
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                }else{
                    $arrData['url_portada'] = media().'/images/uploads/'.$arrData['portada'];
                    $arrResponse = array('status' => true, 'data' => $arrData);
                    }                    
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }
        die();
    }

    public function delModelo(){
        if($_POST){	
            if($_SESSION['permisosMod']['d']){	
                $intIdModelo = intval($_POST['idModelo']);
                $requestDelete = $this->model->deleteModelo($intIdModelo);
                if($requestDelete == 'ok'){
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Modelo');
                }else if($requestDelete == 'exist'){
                    $arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Modelo con Uniformes asociados.');
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Modelo.');
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);	
            }	
        }
            die();
    }

    public function getSelectModelos(){
        $htmlOptions = "";
        $arrData = $this->model->selectModelos();
        if(count($arrData) > 0){
            for ($i=0; $i < count($arrData); $i++) {
                if($arrData[$i]['status'] == 1){
                    $htmlOptions .= '<option value="'.$arrData[$i]['id_categoria'].'">'.$arrData[$i]['nombre'].'</option>';
                }
            }
        }
        echo $htmlOptions;
        die();
    }


}
?>