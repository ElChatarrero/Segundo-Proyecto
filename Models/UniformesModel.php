<?php 

	class UniformesModel extends Mysql
	{
        private $intIdUniformes;
        private $strNombre;
        private $strDescripcion;
        private $intCodigo;
        private $intModeloId;
        private $intPrecio;
        private $intStock;
        private $intStatus;
        private $strRuta;
        private $strImagen;

		public function __construct()
		{
			parent::__construct();
		}

        public function selectUniformes(){
            $sql = "SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.categoria_id, c.nombre AS categoria, u.precio, u.stock, u.status
            FROM uniforme u INNER JOIN categoria c 
            ON u.categoria_id = c.id_categoria
            WHERE u.status != 0 ";
            $request = $this->select_all($sql);
            return $request;
        }

        public function insertUniforme(string $nombre, string $descripcion, int $codigo, int $modeloid,
        string $precio, int $stock, string $ruta, int $status){

            $this->strNombre = $nombre;
            $this->strDescripcion = $descripcion;
            $this->intCodigo = $codigo;
            $this->intModeloId = $modeloid;
            $this->intPrecio = $precio;
            $this->intStock = $stock;
            $this->strRuta = $ruta;
            $this->intStatus = $status;
            $return = 0;
            $sql = "SELECT * FROM uniforme WHERE codigo = '{$this->intCodigo}' ";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert = "INSERT INTO uniforme(categoria_id, codigo, nombre, descripcion, precio, stock, ruta, status)
                VALUES(?,?,?,?,?,?,?,?) ";
                $arrData = array($this->intModeloId, $this->intCodigo, $this->strNombre, $this->strDescripcion, $this->intPrecio,
                $this->intStock, $this->strRuta, $this->intStatus);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
            }else{
                $return = "exist";
            }
            return $return;
        }

        public function updateUniforme(int $iduniforme, string $nombre, string $descripcion, int $codigo, int $modeloid,
        string $precio, int $stock, string $ruta, int $status){

            $this->intIdUniformes = $iduniforme;
            $this->strNombre = $nombre;
            $this->strDescripcion = $descripcion;
            $this->intCodigo = $codigo;
            $this->intModeloId = $modeloid;
            $this->intPrecio = $precio;
            $this->intStock = $stock;
            $this->strRuta = $ruta;
            $this->intStatus = $status;
            $return = 0;
            $sql = "SELECT * FROM uniforme WHERE codigo = '{$this->intCodigo}' AND id_uniforme != $this->intIdUniformes ";
            $request = $this->select_all($sql);
            if(empty($request)){
                $sql = "UPDATE uniforme SET categoria_id=?, codigo=?, nombre=?, descripcion=?, precio=?,
                stock=?, ruta=?, status=?
                WHERE id_uniforme = $this->intIdUniformes ";
                $arrData = array($this->intModeloId, $this->intCodigo, $this->strNombre, $this->strDescripcion,
                $this->intPrecio, $this->intStock, $this->strRuta, $this->intStatus);
                             
                $request = $this->update($sql,$arrData);
                $return = $request;
            }else{
                $return = "exist";
            }            
            return $return;
        }

        public function selectUniforme(int $iduniforme){

            $this->intIdUniformes = $iduniforme;
            $sql = "SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.precio, u.stock, u.categoria_id,
            c.nombre AS modelo, u.status
            FROM uniforme u
            INNER JOIN categoria c
            ON u.categoria_id = c.id_categoria
            WHERE id_uniforme = $this->intIdUniformes ";
            $request = $this->select($sql);
            return $request;
        }

        public function insertImage(int $iduniforme, string $imagen){

            $this->intIdUniformes = $iduniforme;
            $this->strImagen = $imagen;
            $query_insert = "INSERT INTO imagen(uniforme_id,img) VALUES (?,?)";
            $arrData = array($this->intIdUniformes, $this->strImagen);
            $request_insert = $this->insert($query_insert,$arrData);
            return $request_insert;
        }

        public function selectImages(int $iduniforme){
            $this->intIdUniformes = $iduniforme;
            $sql = "SELECT uniforme_id, img FROM imagen
            WHERE uniforme_id = $this->intIdUniformes";
            $request = $this->select_all($sql);
            return $request;
        }

        public function deleteImage(int $iduniforme, string $imagen){
            $this->intIdUniformes = $iduniforme;
            $this->strImagen = $imagen;
            $query = "DELETE FROM imagen WHERE uniforme_id = $this->intIdUniformes
            AND img = '{$this->strImagen}' ";
            $request_delete = $this->delete($query); 
            return $request_delete;
        }

        public function deleteUniforme(int $iduniforme){
            $this->intIdUniformes = $iduniforme;
            $sql = "UPDATE uniforme SET status = ? WHERE id_uniforme = $this->intIdUniformes ";
            $arrData = array(0);
            $request = $this->update($sql,$arrData);
            return $request;
        }

	}
 ?>