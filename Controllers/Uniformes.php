<?php
class Uniformes extends Controllers{
    public function __construct(){
        //sessionStar();
        parent::__construct();
        session_start();
        session_regenerate_id(true);
        if(empty($_SESSION['login'])){
            header('location: '.base_url().'/login');
        }			
        getPermisos(4);
    }

    public function Uniformes(){
        if(empty($_SESSION['permisosMod']['r'])){
            header("Location:".base_url().'/dashboard');
        }
        $data['page_tag'] = "Uniformes";
        $data['page_title'] = "Uniformes";
        $data['page_name'] = "uniformes";
        $data['page_functions_js'] = "functions_uniformes.js";
        $this->views->getView($this,"uniformes",$data);
    }

    public function getUniformes(){
        if($_SESSION['permisosMod']['r']){
        $arrData = $this->model->selectUniformes();		

        for ($i=0; $i < count($arrData); $i++) {
            $btnView = '';
            $btnEdit = '';
            $btnDelete = '';

            if($arrData[$i]['status'] == 1){
                $arrData[$i]['status'] =' <span class="badge badge-success">Activo</span>';
                }else{
                $arrData[$i]['status'] =' <span class="badge badge-danger">Inactivo</span> ';
                }

                $arrData[$i]['precio'] = SMONEY.' '.formatMoney($arrData[$i]['precio']);


                if($_SESSION['permisosMod']['r']){
                $btnView = '<button class="btn btn-info btn-sm"  onClick="fntViewInfo('.$arrData[$i]['id_uniforme'].')" title="Ver Uniforme"><i class="far fa-eye"></i></button>';
                }

                if($_SESSION['permisosMod']['u']){			
                $btnEdit = '<button class="btn btn-primary btn-sm"  onClick="fntEditInfo(this,'.$arrData[$i]['id_uniforme'].')" title="Editar Uniforme"><i class="fa-solid fa-pencil"></i></button>';	
                }

                if($_SESSION['permisosMod']['d']){					
                $btnDelete = '<button class="btn btn-danger btn-sm"  onClick="fntDelInfo('.$arrData[$i]['id_uniforme'].')" title="Eliminar Uniforme"><i class="fa-solid fa-trash"></i></button>';			
                }

                $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
            }		
            echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setUniforme(){

        if($_POST){                    

           if(empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) || empty($_POST['listModelo']) ||
            empty($_POST['txtPrecio']) || empty($_POST['listStatus']) ){
               $arrResponse = array("status" => false, "msg" => 'Datos incorrectos');
           }else{
               $idUniforme = intval($_POST['idUniforme']);
               $strNombre = strClean($_POST['txtNombre']);
               $strDescripcion = strClean($_POST['txtDescripcion']);
               $strCodigo = strClean($_POST['txtCodigo']);
               $intModeloId = intval($_POST['listModelo']);
               $strPrecio = strClean($_POST['txtPrecio']);
               $intStock = intval($_POST['txtStock']);
               $intStatus = intval($_POST['listStatus']);
               $request_uniforme = "";

               $ruta = strtolower(clear_cadena($strNombre));
               $ruta = str_replace(" ","-",$ruta);

               if($idUniforme == 0){
                $option = 1;
                if($_SESSION['permisosMod']['w']){
                $request_uniforme = $this->model->insertUniforme($strNombre, $strDescripcion, $strCodigo, $intModeloId, $strPrecio, $intStock, $ruta, $intStatus);
                }
               }else{
                $option = 2;
                if($_SESSION['permisosMod']['u']){
                $request_uniforme = $this->model->updateUniforme($idUniforme, $strNombre, $strDescripcion, $strCodigo, $intModeloId, $strPrecio, $intStock, $ruta, $intStatus);
                }
               }               

               if($request_uniforme > 0){

                    if($option == 1){
                        $arrResponse = array('status' => true, 'iduniforme' => $request_uniforme, 'msg' => 'Datos guardados correctamente.');
                    }else{
                        $arrResponse = array('status' => true, 'iduniforme' => $idUniforme, 'msg' => 'Datos Actualizados correctamente.');
                    }

               }else if($request_uniforme == 'exist'){
                $arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un uniforme con el Código Ingresado.');
                }else{
                    $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                    }
           }
           echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
       }	
           die();
   }

   public function getUniforme($iduniforme){

    if($_SESSION['permisosMod']['r']){

    $idUniforme = intval($iduniforme);
    if($idUniforme > 0){
        $arrData = $this->model->selectUniforme($idUniforme);
        if(empty($arrData)){
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
            }else{
            $arrImg = $this->model->selectImages($idUniforme);
                if(count($arrImg) > 0){
                for  ($i=0; $i < count($arrImg); $i++){
                    $arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
                    }
                }
                $arrData['images'] = $arrImg;
                $arrResponse = array('status' => true, 'data' => $arrData);
            }        
            echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
    }
        die();
   }

   public function setImage(){
        if($_POST){

        if(empty($_POST['iduniforme'])){
            $arrResponse = array('status' => false, 'msg' => 'Error de dato.');
            }else{

            $idUniforme = intval($_POST['iduniforme']);
            $foto = $_FILES['foto'];
            $imgNombre = 'uni_'.md5(date('d-m-Y H:m:s')).'.jpg';
            $request_image = $this->model->insertImage($idUniforme, $imgNombre);
            if($request_image){
                $uploadImage = uploadImage($foto,$imgNombre);
                $arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');    
            }else{
                $arrResponse = array('status' => false, 'msg' => 'Error de carga.');
                } 
            }    
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);  
        }
        die();
   }

   public function delFile(){
    if($_POST){
        if(empty($_POST['iduniforme']) || empty($_POST['file'])){
            $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
        }else{
            $idUniforme = intval($_POST['iduniforme']);
            $imgNombre = strClean($_POST['file']);
            $request_image = $this->model->deleteImage($idUniforme,$imgNombre);

            if($request_image){
                $deleteFile = deleteFile($imgNombre);
                $arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
            }else{
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
            }
        }
        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
    }
    die();
   }

   public function delUniforme(){
    if($_POST){
        if($_SESSION['permisosMod']['d']){        
        $idUniforme = intval($_POST['idUniforme']);
        $requestDelete = $this->model->deleteUniforme($idUniforme);
        if($requestDelete){
            $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el uniforme.');
        }else{
            $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el uniforme.');
        }
        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
        }
    }
    die();
   }

}
?>