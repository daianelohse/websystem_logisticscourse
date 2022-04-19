<?php
class Jogo {
	private $_id; //int
	private $_nome; //String
	private $_nomeImagem; //Blob
	private $_detalhesJogo; //Blob

	private function __construct($id, $nome, $nomeImagem, $detalhesJogo) {
		$this->_id = $id;
		$this->_nome = $nome;
		$this->_nomeImagem = $_nomeImagem;
		$this->_detalhesJogo = $_detalhesJogo;
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

	public function getNomeImagem() {
		return $this->_nomeImagem;
	}

	public function setNomeImagem($nomeImagem) {
		$this->_nomeImagem = $nomeImagem;
	}
	
	public function getDetalhesJogo() {
		return $this->_detalhesJogo;
	}
	
	public function setDetalhesJogo($detalhesJogo) {
		$this->_detalhesJogo = $detalhesJogo;
	}
}