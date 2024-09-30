<?php 

	class ClientesModel extends Mysql
	{

        private $intIdUsuario;
        private $strNacionalidad;
        private $strCedula;
        private $strNombre;
        private $strApellido;
        private $intTelefono;
        private $strEmail;
        private $strPassword;
        private $strToken;
        private $intTipoId;
        private $intStatus;
        private $strRif;
        private $strNomFiscal;
        private $strDirFiscal;


		public function __construct()
		{
			parent::__construct();
		}	

    //Funcion para Registrar Clientes
        public function insertCliente(string $nacionalidad, int $cedula, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, string $rif,
        string $nomFiscal, string $dirFiscal){

            $this->strNacionalidad = $nacionalidad;
            $this->strCedula = $cedula;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->strRif = $rif;
            $this->strNomFiscal = $nomFiscal;
            $this->strDirFiscal = $dirFiscal;
            $return = 0;

            $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' OR cedula = '{$this->strCedula}'";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert  = "INSERT INTO persona(nacionalidad,cedula,nombre,apellido,telefono,email_user,password,rol_id,rif,nombre_fiscal,direccion_fiscal) 
                VALUES(?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array($this->strNacionalidad, $this->strCedula, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->intTipoId, $this->strRif,
                $this->strNomFiscal, $this->strDirFiscal);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
                }else{
                    $return = "exist";
                    }
            return $return;
         }

          //Funcion para Traer los datos de los clientes
		public function selectClientes(){           

			$sql = "SELECT  id_persona, nacionalidad, cedula, nombre, apellido, telefono, email_user, status
					FROM persona 
					WHERE rol_id = 4 AND status != 0 ";
			$request = $this->select_all($sql);
			return $request;
		}

        //Para traer los datos de 1 cliente
        public function selectCliente(int $idpersona)
        {
            $this->intIdUsuario = $idpersona;
            $sql = "SELECT id_persona,nacionalidad,cedula,nombre,apellido,telefono,email_user,rif,nombre_fiscal,direccion_fiscal,status,
             to_char(datecreated, 'DD Mon YYYY') as fechaRegistro 
					FROM persona
					WHERE id_persona = $this->intIdUsuario and rol_id = 4";
			$request = $this->select($sql);
			return $request;
        }

             //Actualizar
             public function updateCliente(int $idUsuario, string $nacionalidad, string $cedula, string $nombre, string $apellido, string $telefono, 
             string $email, string $password, string $rif, string $nomFiscal, string $dirFiscal){
     
                 $this->intIdUsuario  = $idUsuario;  
                 $this->strNacionalidad = $nacionalidad;
                 $this->strCedula = $cedula;
                 $this->strNombre = $nombre;
                 $this->strApellido = $apellido;
                 $this->intTelefono = $telefono;
                 $this->strEmail = $email;
                 $this->strPassword = $password;
                 $this->strRif = $rif;
                 $this->strNomFiscal = $nomFiscal;
                 $this->strDirFiscal = $dirFiscal;
     
                 $sql = "SELECT * FROM persona WHERE (email_user = '{$this->strEmail}' AND id_persona != $this->intIdUsuario)
                             OR (cedula = '{$this->strCedula}' AND id_persona != $this->intIdUsuario)";
                         $request = $this->select_all($sql);
     
                         if(empty($request))
                         {
                             if($this->strPassword != "")
                             {
                                 $sql = "UPDATE persona SET nacionalidad=?, cedula=?, nombre=?, apellido=?, telefono=?, email_user=?, password=?, rif=?,
                                 nombre_fiscal=?, direccion_fiscal = ?
                                 WHERE id_persona = $this->intIdUsuario";
                                 $arrData = array($this->strNacionalidad,
                                                  $this->strCedula,
                                                  $this->strNombre,
                                                  $this->strApellido,
                                                  $this->intTelefono,
                                                  $this->strEmail,
                                                  $this->strPassword,
                                                  $this->strRif,
                                                  $this->strNomFiscal,
                                                  $this->strDirFiscal);
                             }else{
                                 $sql = "UPDATE persona SET nacionalidad=?, cedula=?, nombre=?, apellido=?, telefono=?, email_user=?, rif=?,
                                 nombre_fiscal=?, direccion_fiscal=?
                                 WHERE id_persona = $this->intIdUsuario";
                                 $arrData = array($this->strNacionalidad,
                                                  $this->strCedula,  
                                                  $this->strNombre,
                                                  $this->strApellido,
                                                  $this->intTelefono,
                                                  $this->strEmail,
                                                  $this->strRif,
                                                  $this->strNomFiscal,
                                                  $this->strDirFiscal); 
                             }
                             $request = $this->update($sql,$arrData);
     
                         }else{
                             $request = "exist";
                         }
                         return $request;
             }

             public function deleteCliente(int $intIdpersona){
                
            $this->intIdUsuario = $intIdpersona;
            $sql = "UPDATE persona SET status = ? WHERE id_persona = $this->intIdUsuario ";
            $arrData = array(0);
            $request = $this->update($sql,$arrData);
            return $request;
            }
    }
    ?>