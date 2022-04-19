<?php

include_once 'dao/ProfessorTurmaDao.class.php';
include_once '../modelo/ProfessorTurma.class.php';

/**
 * @author Daiane 
 */
class ControleProfessorTurma {

    private $_dao;
    private static $_instanciaProfessorTurma = null;

    private function __construct() {
        $this->_dao = new ProfessorTurmaDao();
    }

    public function novoProfessorTurma($professor, $turma) {
        return new ProfessorTurma($professor, $turma);
    }

    public function cadastrarProfessorTurma(ProfessorTurma $p) {
        return $this->_dao->insert($p);
    }

    public function getProfessorTurma() {
        return $this->_dao->select();
    }

    public function getProfessoresByIdTurma($id) {
        return $this->_dao->selectByIdTurma($id);
    }

    public function getProfessoresByIdTurmaEdicao($id) {
        return $this->_dao->selectByIdTurmaEdicao($id);
    }

    public function deleteByIdTurma($id) {
        return $this->_dao->delete($id);
    }

    public static function getInstancia() {
        if (self::$_instanciaProfessorTurma === null) {
            self::$_instanciaProfessorTurma = new ControleProfessorTurma();
        }
        return self::$_instanciaProfessorTurma;
    }

}
