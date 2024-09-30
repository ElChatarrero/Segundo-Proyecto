<?php
require_once("Libraries/Core/Mysql.php");
trait TUniforme{
    private $con;
    private $strModelo;
    private $intIdModelo;
    private $intIdUniforme;
    private $strUniforme;
    private $cant;
    private $option;
    private $strRuta;

    public function getUniformesT(){
        $this->con = new Mysql();
        $sql = "SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.categoria_id, c.nombre AS categoria, u.precio, u.ruta, u.stock
        FROM uniforme u INNER JOIN categoria c 
        ON u.categoria_id = c.id_categoria
        WHERE u.status != 0 ORDER BY u.id_uniforme DESC ";
        $request = $this->con->select_all($sql);
        if(count($request) > 0){
            for ($c=0; $c < count($request) ; $c++){
                $intIdUniforme = $request[$c]['id_uniforme'];
                $sqlImg = "SELECT img FROM imagen
                WHERE uniforme_id = $intIdUniforme";
                $arrImg = $this->con->select_all($sqlImg);
                if(count($arrImg) > 0){
                    for ($i=0; $i < count($arrImg); $i++){
                        $arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
                    }
                }
                $request[$c]['images'] = $arrImg;
            }
        }
        return $request;
    }

    public function getUniformesModeloT(int $idmodelo, string $ruta){
        $this->intIdModelo = $idmodelo;
        $this->strRuta = $ruta;
        $this->con = new Mysql();
        $sql_mod = "SELECT id_categoria,nombre FROM categoria WHERE id_categoria = $this->intIdModelo";
        $request = $this->con->select($sql_mod);

        if(!empty($request)){

            $this->strModelo = $request['nombre'];

            $sql = "SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.categoria_id, c.nombre AS categoria, u.precio, u.ruta, u.stock
            FROM uniforme u INNER JOIN categoria c 
            ON u.categoria_id = c.id_categoria
            WHERE u.status != 0 AND u.categoria_id = $this->intIdModelo AND c.ruta = '{$this->strRuta}' ";
            $request = $this->con->select_all($sql);
                if(count($request) > 0){
                    for ($c=0; $c < count($request) ; $c++){
                    $intIdUniforme = $request[$c]['id_uniforme'];
                    $sqlImg = "SELECT img FROM imagen
                    WHERE uniforme_id = $intIdUniforme";
                    $arrImg = $this->con->select_all($sqlImg);
                        if(count($arrImg) > 0){
                            for ($i=0; $i < count($arrImg); $i++){
                            $arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
                            }
                        }
                    $request[$c]['images'] = $arrImg;
                    }
                }
                $request = array('idmodelo' => $this->intIdModelo, 'modelo' => $this->strModelo, 'uniforme' => $request);
        }    
        return $request;
    }

    public function getUniformeT(int $iduniforme, string $ruta){
        $this->con = new Mysql();
        $this->intIdUniforme = $iduniforme;
        $this->strRuta = $ruta;
        $sql = " SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.categoria_id, c.nombre AS categoria, c.ruta as ruta_categoria,
         u.precio, u.ruta, u.stock
        FROM uniforme u INNER JOIN categoria c 
        ON u.categoria_id = c.id_categoria
        WHERE u.status != 0 AND u.id_uniforme = $this->intIdUniforme AND u.ruta = '{$this->strRuta}' ";
        $request = $this->con->select($sql);
       if(!empty($request)){
                $intIdUniforme = $request['id_uniforme'];
                $sqlImg = "SELECT img FROM imagen
                WHERE uniforme_id = $intIdUniforme";
                $arrImg = $this->con->select_all($sqlImg);
                if(count($arrImg) > 0){
                    for ($i=0; $i < count($arrImg); $i++){
                        $arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
                    }
                }else{
                    $arrImg[0]['url_image'] = media().'/images/uploads/product.png';
                }
                $request['images'] = $arrImg;
        }
        return $request;
    }

    public function getUniformesRandom(int $idmodelo, int $cant, string $option){
        $this->intIdModelo = $idmodelo;
        $this->cant = $cant;
        $this->option = $option;
        $this->con = new Mysql(); 
        
        if($option == "r"){
            $this->option = " RANDOM() ";
        }else if($option == "a"){
            $this->option = " id_uniforme ASC ";
        }else{
            $this->option = " id_uniforme DESC ";
        }
            $sql = "SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.categoria_id, c.nombre AS categoria, u.precio, u.ruta, u.stock
            FROM uniforme u INNER JOIN categoria c 
            ON u.categoria_id = c.id_categoria
            WHERE u.status != 0 AND u.categoria_id = $this->intIdModelo
            ORDER BY $this->option LIMIT $this->cant ";           
            $request = $this->con->select_all($sql);
                if(count($request) > 0){
                    for ($c=0; $c < count($request) ; $c++){
                    $intIdUniforme = $request[$c]['id_uniforme'];
                    $sqlImg = "SELECT img FROM imagen
                    WHERE uniforme_id = $intIdUniforme";
                    $arrImg = $this->con->select_all($sqlImg);
                        if(count($arrImg) > 0){
                            for ($i=0; $i < count($arrImg); $i++){
                            $arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
                            }
                        }
                    $request[$c]['images'] = $arrImg;
                    }
                }       
        return $request;
    }

    public function getUniformeIDT(int $iduniforme){
        $this->con = new Mysql();
        $this->intIdUniforme = $iduniforme;        
        $sql = " SELECT u.id_uniforme, u.codigo, u.nombre, u.descripcion, u.categoria_id, c.nombre AS categoria, u.precio, u.ruta, u.stock
        FROM uniforme u INNER JOIN categoria c 
        ON u.categoria_id = c.id_categoria
        WHERE u.status != 0 AND u.id_uniforme = $this->intIdUniforme";
        $request = $this->con->select($sql);
       if(!empty($request)){
                $intIdUniforme = $request['id_uniforme'];
                $sqlImg = "SELECT img FROM imagen
                WHERE uniforme_id = $intIdUniforme";
                $arrImg = $this->con->select_all($sqlImg);
                if(count($arrImg) > 0){
                    for ($i=0; $i < count($arrImg); $i++){
                        $arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
                    }
                }else{
                    $arrImg[0]['url_image'] = media().'/images/uploads/product.png';
                }
                $request['images'] = $arrImg;
        }
        return $request;
    }


}

?>