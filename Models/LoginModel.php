<?php 

	class LoginModel extends Mysql
	{
        private $intIdUsuario;
        private $strUsuario;
        private $strPassword;
        private $strToken;

		public function __construct()
		{
			parent::__construct();
		}	

        public function loginUser(string $usuario, string $password)
        {
            $this->strUsuario = $usuario;
            $this->strPassword = $password;
            $sql = "SELECT id_persona,status FROM persona WHERE
            email_user = '$this->strUsuario' and 
            password = '$this->strPassword' and
            status != 0";            
            $request = $this->select($sql);
            return $request;
        }

        public function sessionLogin(int $iduser)
        {
            $this ->intIdUsuario = $iduser;
            $sql = "SELECT p.id_persona, p.nacionalidad, p.cedula, p.nombre, p.apellido, p.telefono, p.email_user, p.nombre_fiscal, p.direccion_fiscal, r.id_rol, r.nombre_rol, p.status, p.rif
            FROM persona p INNER JOIN rol r ON p.rol_id = r.id_rol WHERE p.id_persona = $this->intIdUsuario";
            $request = $this->select($sql);
            $_SESSION['userData'] = $request;
            return $request;
        }

        public function getUserEmail(string $strEmail)
        {
            $this->strUsuario = $strEmail;
            $sql = "SELECT id_persona, nombre, apellido, status FROM persona WHERE
            email_user = '$this->strUsuario' and status = 1";
            $request = $this->select($sql);
            return $request;
        }

        public function setTokenUser(int $idpersona, string $token)
        {
            $this->intIdUsuario = $idpersona;
            $this->strToken = $token;
            $sql = "UPDATE persona SET token = ? WHERE id_persona = $this->intIdUsuario ";
            $arrData = array($this->strToken);
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function getUsuario(string $email, string $token)
        {
            $this->strUsuario = $email;
            $this->strToken = $token;
            $sql= "SELECT id_persona FROM persona WHERE email_user = '$this->strUsuario' and token = '$this->strToken' and status = 1 ";
            $request = $this->select($sql);
            return $request;
        }

        public function insertPassword(int $idpersona, string $password)
        {
            $this->intIdUsuario = $idpersona;
            $this->strPassword = $password;
            $sql = "UPDATE persona SET password = ?, token = ? WHERE id_persona = $this->intIdUsuario";
            $arrData = array($this->strPassword,"");
            $request = $this->update($sql,$arrData);
            return $request;
        }

	}
 ?>