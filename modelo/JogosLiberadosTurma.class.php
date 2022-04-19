<?php

/**
 * @author Daiane
 */
class JogosLiberadosTurma {

    private $_idJogo; //int
    private $_idTurma; //int

    public function __construct($jogo, $turma) {
        $this->_idJogo = $jogo;
        $this->_idTurma = $turma;
    }

    //Getters e Setters
    public function getIdJogo() {
        return $this->_idJogo;
    }

    public function setIdJogo($idJogo) {
        $this->_idJogo = $idJogo;
    }

    public function getIdTurma() {
        return $this->_idTurma;
    }

    public function setIdTurma($turma) {
        $this->_idTurma = $turma;
    }

}
