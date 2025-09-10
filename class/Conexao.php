<?php 


class Conexao{

	private static $local = 'localhost';
	private static $banco = 'bio_ubs';
	private static $usuario = 'root';
	private static $senha = '';


	private static $instance;



	public static function getConn(){
		try{
			if (!isset(self::$instance)) :

			self::$instance = new PDO('mysql:host='.self::$local. ';dbname='.self::$banco, self::$usuario, self::$senha);

			//-------------acrescente estas duas linhas para dar mais segurança-----------
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