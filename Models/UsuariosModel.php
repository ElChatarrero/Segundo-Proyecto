<?php 

	class UsuariosModel extends Mysql
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

    //Funcion para Registrar Usuarios
        public function insertUsuario(string $nacionalidad, int $cedula, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status){

            $this->strNacionalidad = $nacionalidad;
            $this->strCedula = $cedula;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->intStatus = $status;
            $return = 0;

            $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' OR cedula = '{$this->strCedula}'";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert  = "INSERT INTO persona(nacionalidad,cedula,nombre,apellido,telefono,email_user,password,rol_id,status) VALUES(?,?,?,?,?,?,?,?,?)";
                $arrData = array($this->strNacionalidad, $this->strCedula, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->intTipoId, $this->intStatus);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
                }else{
                    $return = "exist";
                    }
            return $return;
         }

         //Funcion para Traer los datos de los usuarios
		public function selectUsuarios()
		{
            $whereAdmin	="";
            if($_SESSION['idUser'] != 1)
            {
                $whereAdmin = " and p.id_persona != 1";
            }

			$sql = "SELECT  p.id_persona,p.nacionalidad,p.cedula,p.nombre,p.apellido,p.telefono,p.email_user,p.status,r.id_rol,r.id_rol,r.nombre_rol
					FROM persona p 
					INNER JOIN rol r
					ON p.rol_id = r.id_rol
					WHERE p.status != 0".$whereAdmin;
					$request = $this->select_all($sql);
					return $request;
		}

        //Para traer los datos de 1 usuario
        public function selectUsuario(int $idpersona)
        {
            $this->intIdUsuario = $idpersona;
            $sql = "SELECT p.id_persona,p.nacionalidad,p.cedula,p.nombre,p.apellido,p.telefono,p.email_user,r.id_rol,r.nombre_rol,p.status,
             to_char(p.datecreated, 'DD Mon YYYY') as fechaRegistro 
					FROM persona p
					INNER JOIN rol r
					ON p.rol_id = r.id_rol
					WHERE p.id_persona = $this->intIdUsuario";
			$request = $this->select($sql);
			return $request;
        }
 
            //Actualizar
        public function updateUsuario(int $idUsuario, string $nacionalidad, string $cedula, string $nombre, string $apellido, string $telefono, 
        string $email, string $password, int $tipoid, int $status)
        {

            $this->intIdUsuario  = $idUsuario;  
            $this->strNacionalidad = $nacionalidad;
            $this->strCedula = $cedula;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->intStatus = $status;

            $sql = "SELECT * FROM persona WHERE (email_user = '{$this->strEmail}' AND id_persona != $this->intIdUsuario)
                        OR (cedula = '{$this->strCedula}' AND id_persona != $this->intIdUsuario)";
                    $request = $this->select_all($sql);

                    if(empty($request))
                    {
                        if($this->strPassword != "")
                        {
                            $sql = "UPDATE persona SET nacionalidad=?, cedula=?, nombre=?, apellido=?, telefono=?, email_user=?, password=?, rol_id=?,
                            status=?
                            WHERE id_persona = $this->intIdUsuario";
                            $arrData = array($this->strNacionalidad,
                                             $this->strCedula,
                                             $this->strNombre,
                                             $this->strApellido,
                                             $this->intTelefono,
                                             $this->strEmail,
                                             $this->strPassword,
                                             $this->intTipoId,
                                             $this->intStatus);
                        }else{
                            $sql = "UPDATE persona SET nacionalidad=?, cedula=?, nombre=?, apellido=?, telefono=?, email_user=?, rol_id=?,
                            status=?
                            WHERE id_persona = $this->intIdUsuario";
                            $arrData = array($this->strNacionalidad,
                                             $this->strCedula,  
                                             $this->strNombre,
                                             $this->strApellido,
                                             $this->intTelefono,
                                             $this->strEmail,
                                             $this->intTipoId,
                                             $this->intStatus);
                            
                        }
                        $request = $this->update($sql,$arrData);

                    }else{
                        $request = "exist";
                    }
                    return $request;
        }

        public function deleteUsuario(int $intIdpersona)
        {
            $this->intIdUsuario = $intIdpersona;
            $sql = "UPDATE persona SET status = ? WHERE id_persona = $this->intIdUsuario ";
            $arrData = array(0);
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function existePersona($nacionalidad, $cedula){

			$this->strNacionalidad = $nacionalidad;
			$this->strCedula = $cedula;

		$sql = "SELECT count(*) as cantidad FROM persona WHERE nacionalidad='$this->strNacionalidad' and cedula= '$this->strCedula'";
		$request = $this->select($sql);
		$cantidad = $request['cantidad'];
		return $cantidad;
		}

		public function existeEmail($email){

			$this->strEmail = $email;

		$sql = "SELECT count(*) as cantidad FROM persona WHERE email_user='$this->strEmail'";
		$request = $this->select($sql);
		$cantidad = $request['cantidad'];
		return $cantidad;
		}

        public function updatePerfil(int $idUsuario, string $nacionalidad, int $cedula, string $nombre, string $apellido, int $telefono,
        string $password){

            $this->intIdUsuario = $idUsuario;
            $this->strNacionalidad = $nacionalidad;
            $this->strCedula = $cedula;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strPassword = $password;

            if($this->strPassword != "")
            {
                $sql = "UPDATE persona SET nacionalidad = ?, cedula = ?, nombre = ?, apellido = ?, telefono = ?, password = ?
                WHERE id_persona = $this->intIdUsuario ";
                $arrData = array($this->strNacionalidad, $this->strCedula, $this->strNombre, $this->strApellido, $this->intTelefono,
                $this->strPassword);
            }else{
                $sql = "UPDATE persona SET nacionalidad = ?, cedula = ?, nombre = ?, apellido = ?, telefono = ?
                WHERE id_persona = $this->intIdUsuario ";
                $arrData = array($this->strNacionalidad, $this->strCedula, $this->strNombre, $this->strApellido, $this->intTelefono);
            }
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function updateDataFiscal(int $idUsuario, string $strRif, string $strNomFiscal, string $strDirFiscal){

            $this->intIdUsuario = $idUsuario;
            $this->strRif = $strRif;
            $this->strNomFiscal = $strNomFiscal;
            $this->strDirFiscal = $strDirFiscal;
            $sql = "UPDATE persona SET rif = ?, nombre_fiscal = ?, direccion_fiscal = ?
            WHERE id_persona = $this->intIdUsuario ";
            $arrData = array($this-> strRif, $this->strNomFiscal, $this->strDirFiscal);
            $request = $this->update($sql,$arrData);
            return $request;
        }
        
	}
 ?>




