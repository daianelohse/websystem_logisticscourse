<?php
class Turma {
	private $_id; //int
	private $_nome; //String
	

	//public function __construct() {}

	public function __construct($id, $nome) {
		$this->_id = $id;
		$this->_nome = $nome;
	}

	//Getters e Setters
	public function getId() {
		return $this->_id;
	}

	public function setId($Id) {
		$this->_id = $Id;
	}

	public function getNome() {
		return $this->_nome;
	}

	public function setNome($nome) {
		$this->_nome = $nome;
	}

}