<?php

/**
 * Controla o tempo jogado por aluno
 *
 * @author Daiane
 */
class Relatorio {

    private $_idRelatorio; //Relatorio
    private $_idJogo; //Jogo
    private $_idAluno; //Aluno
    private $_idUsuario; //Usuário
    private $_idTurma; //Turma
    private $_tempo; //Time
    private $_data; //Date

    public function __construct($idRelatorio, $idJogo, $idAluno, $idUsuario, $tempo, $data) {
        $this->_idRelatorio = $idRelatorio;
        $this->_idJogo = $idJogo;
        $this->_idAluno = $idAluno;
        $this->_idUsuario = $idUsuario;
        $this->_tempo = $tempo;
        $this->_data = $data;
    }

    //Getters e Setters
     public function getIdRelatorio() {
        return $this->_idRelatorio;
    }

    public function setIdRelatorio($id) {
        $this->_idRelatorio = $id;
    }
    
    public function getIdJogo() {
        return $this->_idJogo;
    }

    public function setIdJogo($id) {
        $this->_idJogo = $id;
    }

    public function getIdAluno() {
        return $this->_idAluno;
    }

    public function setIdAluno($id) {
        $this->_idAluno = $id;
    }

    public function getIdTurma() {
        return $this->_idTurma;
    }

    public function setIdTurma($id) {
        $this->_idTurma = $id;
    }

    public function getIdUsuario() {
        return $this->_idUsuario;
    }

    public function setIdUsuario($id) {
        $this->_idUsuario = $id;
    }

    public function getTempo() {
        return $this->_tempo;
    }

    public function setTempo($tempo) {
        $this->_tempo = $tempo;
    }

    public function getData() {
        return $this->_data;
    }

    public function setData($data) {
        $this->_data = $data;
    }

}
