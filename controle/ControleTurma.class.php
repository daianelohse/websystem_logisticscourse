<?php

include_once 'dao/TurmaDao.class.php';
include_once '../modelo/Turma.class.php';

class ControleTurma {

    private $_dao;
    private static $_instanciaTurma = null;

    private function __construct() {
        $this->_dao = new TurmaDao();
    }

    public function novaTurma($id, $nome) {
        return new Turma($id, $nome);
    }

    public function cadastrarTurma(Turma $t) {
        return $this->_dao->insert($t);
    }

    public function getTurmas() {
        return $this->_dao->select();
    }

    public function getTurmaById($id) {
        return $this->_dao->selectById($id);
    }

    public function getTurmasByIdProfessor($id) {
        return $this->_dao->selectByIdProfessor($id);
    }

    public function getTurmasByIdSemEsteProfessor($id) {
        return $this->_dao->selectByIdSemEsteProfessor($id);
    }

    public function getTurmaByNome($nome) {
        return $this->_dao->selectByNome($nome);
    }

    public function getUltimoId() {
        return $this->_dao->getlastInsertId();
    }

    public function editarTurma($id, $nome) {
        return $this->_dao->update($id, $nome);
    }

    public function arquivarTurma($id) {
        return $this->_dao->arquivar($id);
    }

    public function abrirTurmaArquivada($id) {
        return $this->_dao->abrirTurma($id);
    }

    public function getNumeroTurmasArquivadas() {
        return $this->_dao->numeroTurmasArquivadas();
    }

    public function getNumeroTurmasArquivadasByProfessor($id) {
        return $this->_dao->numeroTurmasArquivadasByProfessor($id);
    }

    public function getTurmasArquivadas() {
        return $this->_dao->selectTurmasArquivadas();
    }

    public function getTurmasArquivadasByIdProfessor($id) {
        return $this->_dao->selectTurmasArquivadasByProfessor($id);
    }

    public function delete($id) {
        return $this->_dao->delete($id);
    }

    public static function getInstancia() {
        if (self::$_instanciaTurma === null) {
            self::$_instanciaTurma = new ControleTurma();
        }
        return self::$_instanciaTurma;
    }

}
