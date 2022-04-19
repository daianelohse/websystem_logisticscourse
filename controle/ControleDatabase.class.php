<?php
include_once '../modelo/Database.class.php';

class ControleDatabase {
	
	private static $_instanciaDb = null;
	
	private function __construct() {}
	
	public function __destruct() {
		$this->disconnect ();
		foreach($this as $key => $value) {
			unset( $this->$key );
		}
	}
	
	public function connect() {
		$_config = new Database();
		try {
			$this->conexao = new PDO ( $_config->getDBType () . ":host=" . $_config->getHost () . ";port=" . $_config->getPort () . ";dbname=" . $_config->getDB (), $_config->getUser (), $_config->getPassword () );
		} catch(PDOException $i) {
			// se houver exceção, exibe
			die("Erro: <code>" . $i->getMessage() . "</code>" );
		}
		
		return ($this->conexao);
	}
        
	
	public function disconnect() {
		$this->conexao = null;
	}
	
	public static function getInstancia() {
		if (self::$_instanciaDb == null) {
			self::$_instanciaDb = new ControleDatabase();
		}
		return self::$_instanciaDb;
	}
}

?>