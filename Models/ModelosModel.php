<?php 

	class ModelosModel extends Mysql
	{
        public $intIdModelo;
        public $strModelo;
        public $strDescripcion;
        public $intStatus;
        public $strPortada;
        public $strRuta;

		public function __construct()
		{
			parent::__construct();
		}	

        //Insertar Roles a la base de datos
        public function insertModelo(string $nombre, string $descripcion, string $portada, string $ruta, int $status){
            $return = 0;
            $this->strModelo = $nombre;
            $this->strDescripcion = $descripcion;
            $this->strPortada = $portada;
            $this->strRuta = $ruta;
            $this->intStatus = $status;
            

            $sql = "SELECT * FROM categoria WHERE nombre = '$this->strModelo' ";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert = "INSERT INTO categoria(nombre,descripcion,portada,ruta,status) VALUES(?,?,?,?,?)";
                $arrData = array($this->strModelo, $this->strDescripcion, $this->strPortada, $this->strRuta, $this->intStatus);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
            }else{
                $return = "exist";
            }
            return $return;
        }

        public function selectModelos(){           

			$sql = "SELECT  * FROM categoria WHERE status != 0 ";
			$request = $this->select_all($sql);
			return $request;
		}

        public function selectModelo(int $idmodelo){
            $this->intIdModelo = $idmodelo;
            $sql = "SELECT * FROM categoria
             WHERE id_categoria = $this->intIdModelo";
             $request = $this->select($sql);
             return $request;
        }

        public function updateModelo(int $idmodelo, string $modelo, string $descripcion, string $portada, string $ruta, int $status){

            $this->intIdModelo = $idmodelo;
            $this->strModelo = $modelo;
            $this->strDescripcion = $descripcion;
            $this->strPortada = $portada;
            $this->strRuta = $ruta;
            $this->intStatus = $status;

            $sql = "SELECT * FROM categoria WHERE nombre = '{$this->strModelo}' AND id_categoria != $this->intIdModelo ";
            $request = $this->select_all($sql);

            if(empty($request)){
                $sql = "UPDATE categoria SET nombre = ?, descripcion = ?, portada = ?, ruta = ?, status = ? WHERE id_categoria = $this->intIdModelo ";
                $arrData = array($this->strModelo, $this->strDescripcion, $this->strPortada, $this->strRuta, $this->intStatus);
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }

        public function deleteModelo(int $idmodelo){
			$this->intIdModelo = $idmodelo;
			$sql = "SELECT * FROM uniforme WHERE categoria_id = $this->intIdModelo";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE categoria SET status = ? WHERE id_categoria = $this->intIdModelo ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}

	}
 ?>