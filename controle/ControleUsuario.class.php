<?php

include_once 'dao/UsuarioDao.class.php';
include_once '../modelo/Usuario.class.php';

class ControleUsuario {

    private $_dao;
    private static $_instanciaUsuario = null;

    private function __construct() {
        $this->_dao = new UsuarioDao();
    }

    public function novoUsuario($id, $nome, $user, $email, $senha, $tipoUsuario, $estaAprovado) {
        return new Usuario($id, $nome, $user, $email, $senha, $tipoUsuario, $estaAprovado);
    }

    public function cadastrarUsuario(Usuario $u) {
        return $this->_dao->insert($u);
    }
    
     public function editarUsuario(Usuario $u) {
        return $this->_dao->update($u);
    }

    public function getUsuarios() {
        return $this->_dao->select();
    }

    public function getUsuarioById($id) {
        return $this->_dao->selectById($id);
    }

    public function getUsuarioByUser($id, $user) {
        return $this->_dao->selectByUser($id, $user);
    }

    public function getUsuarioByEmail($id, $email) {
        return $this->_dao->selectByEmail($id, $email);
    }
    
     public function getUsuarioByUserEmail($userEmail) {
        return $this->_dao->selectByEmailUser($userEmail);
    }


    public function getUsuariosComPermissaoAlunoSemTurma() {
        return $this->_dao->getUsuariosComPermissaoAlunoSemTurma();
    }
    
    public function getUsuariosComPermissao($pesquisa) {
        return $this->_dao->selectComPermissao($pesquisa);
    }

    public function getUsuariosSemPermissao($pesquisa) {
        return $this->_dao->getUsuariosSemPermissao($pesquisa);
    }
    
    public function getUsuariosInativos($pesquisa) {
        return $this->_dao->getInativos($pesquisa);
    }

    public function setPermissaoUsuario($id, $tipo) {
        return $this->_dao->setPermissaoUsuario($id, $tipo);
    }

    public function validarLogin($user, $senha) {
        return $this->_dao->selectValidarLogin($user, $senha);
    }
    
     public function deleteUsuario($id) {
        return $this->_dao->deleteById($id);
    }
    

    public static function getInstancia() {
        if (self::$_instanciaUsuario === null) {
            self::$_instanciaUsuario = new ControleUsuario();
        }
        return self::$_instanciaUsuario;
    }

}
