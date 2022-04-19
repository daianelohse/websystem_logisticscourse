<?php

include_once 'dao/AlunoDao.class.php';
include_once '../modelo/Aluno.class.php';

class ControleAluno {

    private $_dao;
    private static $_instanciaAluno = null;

    private function __construct() {
        $this->_dao = new AlunoDao();
    }

    public function novoAluno($id, $usuario, $estaAtivo) {
        return new Aluno($id, $usuario, $estaAtivo);
    }

    public function cadastrarAluno(Aluno $a) {
        return $this->_dao->insert($a);
    }

    public function getAlunos() {
        return $this->_dao->select();
    }

    public function getAlunoById($id) {
        return $this->_dao->selectById($id);
    }

    public function getAlunosByIdTurma($id) {
        return $this->_dao->selectByIdTurma($id);
    }
    
    public function getAlunosSemTurmaByNome($nome, $idTurma) {
        return $this->_dao->selectByNome($nome, $idTurma);
    }

    public function getAlunosByIdUser($id) {
        return $this->_dao->selectByIdUser($id);
    }

    public function getTurmasArquivadasByIdProfessor() {
        return $this->_dao->selectTurmasArquivadasByIdProefessor();
    }
    

    public function deleteAlunoTurma($id) {
        return $this->_dao->deleteAlunoTurma($id);
    }

    public function deleteByIdTurma($id) {
        return $this->_dao->deleteByIdTurma($id);
    }
    
    
    public function deleteAlunoTurmaByIdUser($id) {
        return $this->_dao->deleteAlunoTurmaByIdUser($id);
    }
    
    public function updateAluno($id, $ativo) {
		return $this->_dao->update($id, $ativo);
	}
    
    public static function getInstancia() {
        if (self::$_instanciaAluno === null) {
            self::$_instanciaAluno = new ControleAluno();
        }
        return self::$_instanciaAluno;
    }

}
