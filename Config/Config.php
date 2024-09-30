<?php 
	
	const BASE_URL = "http://localhost/tienda_virtual";

	//Zona horaria
	date_default_timezone_set('America/Caracas');

	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "tienda_virtual";
	const DB_USER = "postgres";
	const DB_PASSWORD = "12345678";
	const DB_CHARSET = "utf8";

	//Deliminadores decimal y millar Ej. 24.1989,00
	const SPD = ",";
	const SPM = ".";

	//Simbolo de moneda
	const SMONEY = "$";
	const CURRENCY = "USD";

	//Datos envio de correo
	const NOMBRE_REMITENTE = "Tienda de Uniformes Osh";
	const EMAIL_REMITENTE = " no-reply@uniformesOsh.com";

	const NOMBRE_EMPRESA = "Uniformes Osh";
	const WEB_EMPRESA = "www.uniformesosh.com";	

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "4,5,6";

	//Datos para Encriptar / Desencriptar
	const KEY = 'OSH';
	const METHODENCRIPT = "AES-128-ECB";

	//Envio
	const COSTOENVIO = 5;

 ?>