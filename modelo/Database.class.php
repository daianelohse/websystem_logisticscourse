<?php
class Database {
	
	/* Método construtor do banco de dados */
	public function __construct() {
	}
	
	/* Evita que a classe seja clonada */
	public function __clone() {
	}
	
	private static $dbtype = "mysql";
	private static $host = "localhost";
	private static $port = "3306";
	private static $user = "root";
	private static $password = "";
	private static $db = "logistica";
	
	public function getDBType() {
		return self::$dbtype;
	}
	public function getHost() {
		return self::$host;
	}
	public function getPort() {
		return self::$port;
	}
	public function getUser() {
		return self::$user;
	}
	public function getPassword() {
		return self::$password;
	}
	public function getDB() {
		return self::$db;
	}
	
}
?>