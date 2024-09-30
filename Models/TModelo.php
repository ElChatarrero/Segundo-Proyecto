<?php
require_once("Libraries/Core/Mysql.php");
trait TModelo{
    private $con;

    public function getModelosT(string $modelo){
        $this->con = new Mysql();
        $sql = "SELECT id_categoria, nombre, descripcion, portada, ruta
        FROM categoria WHERE status != 0 AND id_categoria IN ($modelo)";
        $request = $this->con->select_all($sql);
        if(count($request) > 0){
            for ($c=0; $c < count($request) ; $c++) {
                $request[$c]['portada'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['portada'];
            }
        }
        return $request;
    }
}
?>