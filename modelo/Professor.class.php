<?php

class Professor {

    private $_id; //int
    private $_usuario; //int
    private $_estaAtivo; // TRUE: perfil ativo \ FALSE: perfil desativado 

    //public function __construct() {}

    public function __construct($id, $usuario, $estaAtivo) {
        $this->_id = $id;
        $this->_usuario = $usuario;
        $this->_estaAtivo = $estaAtivo;
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
