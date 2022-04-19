<?php

class Aluno {

    private $_id; //int
    private $_usuario; //int
    private $_estaAtivo; //int

    //public function __construct() {}

    public function __construct($id, $usuario, $ativo) {
        $this->_id = $id;
        $this->_usuario = $usuario;
        $this->_estaAtivo = $ativo;
    }

    //Getters e Setters
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getUsuario() {
        return $this->_usuario;
    }

    public function setUsuario($user) {
        $this->_usuario = $user;
    }
    
    public function estaAtivo() {
        return $this->_estaAtivo;
    }

    public function setEstaAtivo($ativo) {
        $this->_estaAtivo = $ativo;
    }
    
}
