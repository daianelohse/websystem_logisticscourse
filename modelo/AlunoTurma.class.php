<?php

/**
 * @author Daiane
 */
class AlunoTurma {

    private $_aluno; //int
    private $_turma; //int

    //public function __construct() {}

    public function __construct($aluno, $turma) {
        $this->_aluno = $aluno;
        $this->_turma = $turma;
    }

    //Getters e Setters
    public function getAluno() {
        return $this->_aluno;
    }

    public function setAluno($aluno) {
        $this->_aluno = $aluno;
    }

    public function getTurma() {
        return $this->_turma;
    }

    public function setTurma($turma) {
        $this->_turma = $turma;
    }

}
