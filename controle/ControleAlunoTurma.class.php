<?php

include_once 'dao/AlunoTurmaDao.class.php';
include_once '../modelo/AlunoTurma.class.php';

/**
 * @author Daiane
 */
class ControleAlunoTurma {
    private $_dao;
    private static $_instanciaAlunoTurma = null;

    private function __construct() {
        $this->_dao = new AlunoTurmaDao();
    }

    public function novoAlunoTurma($aluno, $turma) {
        return new AlunoTurma($aluno, $turma);
    }

    public function cadastrarAlunoTurma(AlunoTurma $at) {
        return $this->_dao->insert($at);
    }

    public function getAlunoTurma() {
        return $this->_dao->select();
    }
    
    public function getAlunoTurmaByIdTurma($id) {
        return $this->_dao->selectByIdTurma($id);
    }
    
     public function getAlunoTurmaByIdAluno($id) {
        return $this->_dao->selectByIdAluno($id);
    }
    
    public function updateAlunoTurma(AlunoTurma $at) {
        return $this->_dao->update($at);
    }
    
     public function deleteByIdTurma($idAluno,$idTurma) {
        return $this->_dao->deleteByIdTurma($idAluno,$idTurma);
    }

    public static function getInstancia() {
        if (self::$_instanciaAlunoTurma === null) {
            self::$_instanciaAlunoTurma = new ControleAlunoTurma();
        }
        return self::$_instanciaAlunoTurma;
    }
}
