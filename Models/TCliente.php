<?php
require_once("Libraries/Core/Mysql.php");
trait TCliente{
    private $con;
    private $intIdUsuario;
    private $strNombre;
    private $strApellido;
    private $intTelefono;
    private $strEmail;
    private $strPassword;
    private $strToken;
    private $intTipoId;
    private $intIdTransaccion;

       //Funcion para Registrar Clientes
    public function insertCliente(string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid){

           $this->con = new Mysql();        
           $this->strNombre = $nombre;
           $this->strApellido = $apellido;
           $this->intTelefono = $telefono;
           $this->strEmail = $email;
           $this->strPassword = $password;
           $this->intTipoId = $tipoid;
           $return = 0;

           $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}'";
           $request = $this->con->select_all($sql);

           if(empty($request)){
               $query_insert  = "INSERT INTO persona(nombre,apellido,telefono,email_user,password,rol_id) 
               VALUES(?,?,?,?,?,?)";
               $arrData = array($this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->intTipoId);
               $request_insert = $this->con->insert($query_insert,$arrData);
               $return = $request_insert;
               }else{
                   $return = "exist";
                   }
           return $return;
        }

        public function insertDetalleTemp(array $pedido){

            $this->intIdUsuario = $pedido['idcliente'];
            $this->intIdTransaccion = $pedido['idtransaccion'];
            $uniformes = $pedido['uniformes'];

            $this->con = new Mysql();
            $sql = "SELECT * FROM detalle_temp WHERE
                    transaccionid = '{$this->intIdTransaccion}' AND
                    personaid = $this->intIdUsuario";
            $request = $this->con->select_all($sql);

            if(empty($request)){
                foreach ($uniformes as $uniforme) {
                    $query_insert = "INSERT INTO detalle_temp(personaid,uniforme_id,precio,cantidad,transaccionid)
                    VALUES (?,?,?,?,?)";
                    $arrData = array($this->intIdUsuario,
                                     $uniforme['iduniforme'],
                                     $uniforme['precio'],
                                     $uniforme['cantidad'],
                                     $this->intIdTransaccion
                                    );
                    $request_insert = $this->con->insert($query_insert,$arrData);
                }
            }else{
                $sqlDel = "DELETE FROM detalle_temp WHERE
                transaccionid = '{$this->intIdTransaccion}' AND
                personaid = $this->intIdUsuario";
                $request = $this->con->delete($sqlDel);
                foreach ($uniformes as $uniforme) {
                    $query_insert = "INSERT INTO detalle_temp(personaid,uniforme_id,precio,cantidad,transaccionid)
                    VALUES (?,?,?,?,?)";
                    $arrData = array($this->intIdUsuario,
                                     $uniforme['iduniforme'],
                                     $uniforme['precio'],
                                     $uniforme['cantidad'],
                                     $this->intIdTransaccion
                                    );
                    $request_insert = $this->con->insert($query_insert,$arrData);
                }
            }

        }
}
?>