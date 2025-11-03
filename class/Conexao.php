<?php 

namespace BioUBS;

use PDO; // <-- ADICIONAR ISTO
use PDOException; // <-- ADICIONAR ISTO

class Conexao{

	 
	private static $local ;
	private static $banco ;
	private static $usuario ;
	private static $senha ;


	private static $instance;



	public static function getConn(){
		try{
			if (!isset(self::$instance)) :
			$config = require __DIR__ . '/../config.php';
			self::$local = $config['local'];
			self::$banco = $config['banco'];
			self::$usuario = $config['usuario'];
			self::$senha = $config['senha'];

			self::$instance = new PDO('mysql:host='.self::$local. ';dbname='.self::$banco .';charset=utf8mb4',self::$usuario, self::$senha);

			//-------------acrescente estas,'charset=utf8mb4' duas linhas para dar mais segurança-----------
			//----------------------------------------------------------------------------
			self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			
			
		    endif;

		     return self::$instance;


		}
		catch(PDOException $ex){

	         echo "Não foi possível te conectar ao banco de dados.***";

	         die;
		}


		
	    
	    

	}//fim da função

}//fim classe












?>