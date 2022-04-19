<?php

class Usuario {

    private $_id; //int
    private $_nome; //String
    private $_user; //String
    private $_email; //String
    private $_senha; //String
    private $_tipoUsuario; //1 - Admin \ 2 - Professor \ 3 - Aluno
    private $_estaAprovado; //TRUE: Acesso liberado \ FALSE: Acesso aguardando aprovação do administrador 
    

    //public function __construct() {}

    public function __construct($id, $nome, $user, $email, $senha, $tipoUsuario, $estaAprovado) {
        $this->_id = $id;
        $this->_nome = $nome;
        $this->_user = $user;
        $this->_email = $email;
        $this->_senha = $senha;
        $this->_tipoUsuario = $tipoUsuario;
        $this->_estaAprovado = $estaAprovado;
        //if (empty($service) || empty($action)) {
        //throw new Exception("Both service and action must have a value");
        //}
    }

    //Getters e Setters
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getNome() {
        return $this->_nome;
    }

    public function setNome($nome) {
        $this->_nome = $nome;
    }

    public function getUser() {
        return $this->_user;
    }

    public function setUser($user) {
        $this->_user = $user;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function setEmail($email) {
        $this->_email = $email;
    }

    public function getSenha() {
        return $this->_senha;
    }

    public function setSenha($senha) {
        $this->_senha = $senha;
    }

    public function getTipoUsuario() {
        return $this->_tipoUsuario;
    }

    public function setTipoUsuario($tipoUsuario) {
        $this->_tipoUsuario = $tipoUsuario;
    }

    public function getEstaAprovado() {
        return $this->_estaAprovado;
    }

    public function setEstaAprovado($estaAprovado) {
        $this->_estaAprovado = $estaAprovado;
    }
  

}
