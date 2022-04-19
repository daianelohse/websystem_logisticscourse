<?php
include_once 'dao/ProfessorDao.class.php';
include_once '../modelo/Professor.class.php';

class ControleProfessor {
	private $_dao;
	private static $_instanciaProfessor = null;

	private function __construct() {
		$this->_dao = new ProfessorDao();
	}

	public function novoProfessor($id, $usuario, $estaAtivo) {
		return new Professor($id, $usuario, $estaAtivo);
	}
	
	public function cadastrarProfessor(Professor $p) {
		return $this->_dao->insert($p);
	}

	public function getProfessores() {
		return $this->_dao->select();
	}
	
	public function getProfessorById($id) {
		return $this->_dao->selectById($id);
	}
        
        public function getProfessorByIdUsuario($id) {
		return $this->_dao->selectByIdUser($id);
	}
        
        public function getProfessorByNome($nome) {
		return $this->_dao->selectByNome($nome);
	}
        
        public function updateProfessor($id, $ativo) {
		return $this->_dao->update($id, $ativo);
	}
	
	
	public static function getInstancia() {
		if (self::$_instanciaProfessor === null) {
			self::$_instanciaProfessor = new ControleProfessor();
		}
		return self::$_instanciaProfessor;
	}
}