<?php
require_once("Models/TModelo.php");
require_once("Models/TUniforme.php");
require_once("Models/TCliente.php");
require_once("Models/LoginModel.php");

	class Tienda extends Controllers{        
		use TModelo, TUniforme, TCliente;
		public $login;
		public function __construct()
		{
			parent::__construct();
			session_start();
			$this->login = new LoginModel();
		}

		public function tienda(){
			$data['page_tag'] = NOMBRE_EMPRESA;
			$data['page_title'] = NOMBRE_EMPRESA;
			$data['page_name'] = "tienda";
			$data['uniformes'] = $this->getUniformesT();		
			$this->views->getView($this,"tienda",$data);
		}

        public function modelo($params){
            if(empty($params)){
                header("Location:".base_url());
            }else{
				
				$arrParams = explode(",",$params);
				$idmodelo = intval($arrParams[0]);
				$ruta = strClean($arrParams[1]);
				$infoModelo = $this->getUniformesModeloT($idmodelo, $ruta);							
                $modelo = strClean($params);              
                $data['page_tag'] =  NOMBRE_EMPRESA." - ".$infoModelo['modelo'];
			    $data['page_title'] = $infoModelo['modelo'];
			    $data['page_name'] = "modelo";
			    $data['uniformes'] = $infoModelo['uniforme'];		
			    $this->views->getView($this,"modelo",$data);
            }
        }

	public function uniformes($params){
			if(empty($params)){
                header("Location:".base_url());
            }else{
				$arrParams = explode(",",$params);
				$iduniforme = intval($arrParams[0]);
				$ruta = strClean($arrParams[1]);
				$infoUniforme = $this->getUniformeT($iduniforme,$ruta);
				if(empty($infoUniforme)){
					header("Location:".base_url());
				}
                $data['page_tag'] =  NOMBRE_EMPRESA." - ".$infoUniforme['nombre'];
			    $data['page_title'] = $infoUniforme['nombre'];
			    $data['page_name'] = "uniforme";
				$data['uniforme'] = $infoUniforme;
			   	$data['uniformes'] = $this->getUniformesRandom($infoUniforme['categoria_id'], 8, "r");		
			    $this->views->getView($this,"uniforme",$data);
			}
	}

	public function addCarrito(){
		if($_POST){
			//unset($_SESSION['arrCarrito']);exit;
			$arrCarrito = array();
			$cantCarrito = 0;
			$iduniforme = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
			$cantidad = $_POST['cant'];
			if(is_numeric($iduniforme) and is_numeric($cantidad)){
				$arrInfoUniforme = $this->getUniformeIDT($iduniforme);
				if(!empty($arrInfoUniforme)){
					$arrUniforme = array('iduniforme' => $iduniforme,
										 'uniforme' => $arrInfoUniforme['nombre'],
										 'cantidad' => $cantidad,
										 'precio' => $arrInfoUniforme['precio'],
										 'imagen' => $arrInfoUniforme['images'][0]['url_image']
										);
					if(isset($_SESSION['arrCarrito'])){
						$on = true;
						$arrCarrito = $_SESSION['arrCarrito'];
						for ($pr=0; $pr < count($arrCarrito); $pr++) {
							if($arrCarrito[$pr]['iduniforme'] == $iduniforme){
								$arrCarrito[$pr]['cantidad'] += $cantidad;
								$on = false;
							}
						}
						if($on){
							array_push($arrCarrito,$arrUniforme);
						}
						$_SESSION['arrCarrito'] = $arrCarrito;
					}else{
						array_push($arrCarrito,$arrUniforme);
						$_SESSION['arrCarrito'] = $arrCarrito;
					}
					foreach ($_SESSION['arrCarrito'] as $uni) {
						$cantCarrito += $uni['cantidad'];
					}
					$htmlCarrito = "";
					$htmlCarrito = getFile('Template/Modals/modalCarrito',$_SESSION['arrCarrito']);
					$arrResponse = array("status" => true,
									     "msg" => '¡Se agrego al carrito',
										 "cantCarrito" => $cantCarrito,
										 "htmlCarrito" => $htmlCarrito);

				}else{
					$arrResponse = array("status" => false, "msg" => 'Uniforme no existente');
				}
			}else{
				$arrResponse = array("status" => false, "msg" => 'Dato incorrecto');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);			
		}
		die();

	}

	public function delCarrito(){
		if($_POST){
			$arrCarrito = array();
			$cantCarrito = 0;
			$subtotal = 0;
			$iduniforme = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
			$option = $_POST['option'];
			if(is_numeric($iduniforme) and ($option == 1 or $option == 2)){
				$arrCarrito = $_SESSION['arrCarrito'];
				for ($pr = 0; $pr < count($arrCarrito); $pr++) {
					if($arrCarrito[$pr]['iduniforme'] == $iduniforme){
						unset($arrCarrito[$pr]);
					}
				}				
				sort($arrCarrito);
				$_SESSION['arrCarrito'] = $arrCarrito;
				foreach ($_SESSION['arrCarrito'] as $uni) {
					$cantCarrito += $uni['cantidad'];
					$subtotal += $uni['cantidad'] * $uni['precio'];
				}
				$htmlCarrito = "";

				if($option == 1){
				$htmlCarrito = getFile('Template/Modals/modalCarrito',$_SESSION['arrCarrito']);
				}
				$arrResponse = array("status" => true,
									     "msg" => '¡Uniforme eliminado',
										 "cantCarrito" => $cantCarrito,
										 "htmlCarrito" => $htmlCarrito,
										 "subTotal" => SMONEY.formatMoney($subtotal),
										 "total" => SMONEY.formatMoney($subtotal + COSTOENVIO)
									);
			}else{
				$arrResponse = array("status" => false, "msg" => 'Dato incorrecto.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function updCarrito(){
		if($_POST){			
			$arrCarrito = array();
			$totalUniforme = 0;
			$subtotal = 0;
			$total = 0;
			$iduniforme = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
			$cantidad = intval($_POST['cantidad']);
			if(is_numeric($iduniforme) and $cantidad > 0){
				$arrCarrito = $_SESSION['arrCarrito'];			
				for ($p=0; $p < count($arrCarrito); $p++) {
					if($arrCarrito[$p]['iduniforme'] == $iduniforme){
						$arrCarrito[$p]['cantidad'] = $cantidad;
						$totalUniforme = $arrCarrito[$p]['precio'] * $cantidad;
						break;
					}
				}
				$_SESSION['arrCarrito'] = $arrCarrito;
				foreach ($_SESSION['arrCarrito'] as $uni){
					$subtotal += $uni['cantidad'] * $uni['precio'];
				}		
				$arrResponse = array("status" => true,
									 "msg" => '¡Producto actualizado!',
									 "totalUniforme" => SMONEY.formatMoney($totalUniforme),
									 "subTotal" => SMONEY.formatMoney($subtotal),
									 "total" => SMONEY.formatMoney($subtotal + COSTOENVIO)
									);

			}else{
				$arrResponse = array("status" => false, "msg" => 'Dato incorrecto.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function registro(){ 			
			
		if($_POST){				
			if(empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmailCliente'])){
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{ 
							
				$strNombre = ucwords(strClean($_POST['txtNombre']));
				$strApellido = ucwords(strClean($_POST['txtApellido']));
				$intTelefono = intval(strClean($_POST['txtTelefono']));
				$strEmail = strtolower(strClean($_POST['txtEmailCliente']));
				$intTipoId = 4;
				$request_user = "";

					$strPassword = passGenerator();
					$strPasswordEncript = hash("SHA256",$strPassword);			
					$request_user = $this->insertCliente($strNombre,
														 $strApellido, 
														 $intTelefono, 
														 $strEmail, 
														 $strPasswordEncript, 
														 $intTipoId
														);
	
				if($request_user > 0 ){
					$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					$nombreUsuario = $strNombre.' '.$strApellido;

					 $dataUsuario = array('nombreUsuario' => $nombreUsuario,
					 'email' => $strEmail,
					 'password' => $strPassword,
					 'asunto' => 'Bienvenido a tu tienda');

					 $_SESSION['idUser'] = $request_user;
					 $_SESSION['login'] = true;
					 $this->login->sessionLogin($request_user);
					//sendEmail($dataUsuario,'email_bienvenida');
									
				}else if($request_user == 'exist'){
					$arrResponse = array('status' => false, 'msg' => '¡Atención! el email.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}	
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function procesarVenta(){

		if($_POST){
			$personaid = $_SESSION['idUser'];
			$monto = 0;
			$tipopagoid = intval($_POST['inttipopago']);
			$direccionenvio = strClean($_POST['direccion']).', '.strClean($_POST['ciudad']);
			$status = "Pendiente";
			$subtotal = 0;

			if(!empty($_SESSION['arrCarrito'])){
				foreach ($_SESSION['arrCarrito'] as $pro){
					$subtotal += $pro['cantidad'] * $pro['precio'];
				}
				$monto = formatMoney($subtotal + COSTOENVIO);


				/*if($request_pedido > 0){


				}*/

				

			}else{
				$arrResponse = array("status" => false, "msg" => 'No es posible procesar el pedido.');
			}
		}else{
			$arrResponse = array("status" => false, "msg" => 'No es posible procesar el pedido.');
		}

		echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}
}
 ?>