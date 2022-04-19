<?php

include_once 'dao/JogosLiberadosTurmaDao.class.php';
include_once '../modelo/JogosLiberadosTurma.class.php';

/**
 * @author Daiane
 */
class ControleJogosLiberadosTurma {

    private $_dao;
    private static $_instanciaJogosLiberadosTurma = null;

    private function __construct() {
        $this->_dao = new JogosLiberadosTurmaDao();
    }

    public function novoJogosLiberadosTurma($idJogo, $idTurma) {
        return new JogosLiberadosTurma($idJogo, $idTurma);
    }

    public function cadastrarJogoLiberadoTurma(JogosLiberadosTurma $j) {
        return $this->_dao->insert($j);
    }
    
    public function getJogosLiberadosByIdTurmaJogo($idJogo, $idTurma) {
        return $this->_dao->selectByIdTurmaJogo($idJogo, $idTurma);
    }
    
    
     public function getJogoLiberadoByUser($idUser) {
         return $this->_dao->selectByUser($idUser);
    }
    
    public function updateJogoLiberadoTurma($idJogo, $idTurma, $estaAtivo) {
         return $this->_dao->update($idJogo, $idTurma, $estaAtivo);
    }
    
     public function deleteByTurma($idTurma) {
         return $this->_dao->delete($idTurma);
    }
    

    public static function getInstancia() {
        if (self::$_instanciaJogosLiberadosTurma === null) {
            self::$_instanciaJogosLiberadosTurma = new ControleJogosLiberadosTurma();
        }
        return self::$_instanciaJogosLiberadosTurma;
    }

}
