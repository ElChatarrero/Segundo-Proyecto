<?php
require_once("Models/TModelo.php");
require_once("Models/TUniforme.php");
	class Home extends Controllers{
		use TModelo, TUniforme;
		public function __construct()
		{
			parent::__construct();
			session_start();
		}

		public function home()
		{
			$data['page_tag'] = NOMBRE_EMPRESA;
			$data['page_title'] = NOMBRE_EMPRESA;
			$data['page_name'] = "Uniformes Osh";
			$data['slider'] = $this->getModelosT(CAT_SLIDER);
			$data['banner'] = $this->getModelosT(CAT_BANNER);
			$data['uniformes'] = $this->getUniformesT();		
			$this->views->getView($this,"home",$data);
		}
	}
 ?>