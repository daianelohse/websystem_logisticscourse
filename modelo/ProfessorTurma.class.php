<?php

/**
 * @author Daiane
 */
class ProfessorTurma {

    private $_professor; //int
    private $_turma; //int

    //public function __construct() {}

    public function __construct($professor, $turma) {
        $this->_professor = $professor;
        $this->_turma = $turma;
    }

    //Getters e Setters
    public function getProfessor() {
        return $this->_professor;
    }

    public function setProfessor($professor) {
        $this->_professor = $professor;
    }

    public function getTurma() {
        return $this->_turma;
    }

    public function setTurma($turma) {
        $this->_turma = $turma;
    }

}
