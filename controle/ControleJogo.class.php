<?php
include_once 'dao/JogoDao.class.php';
class ControleJogo {
    private $_dao;
    private static $_instanciaJogo = null;

    private function __construct() {
        $this->_dao = new JogoDao();
    }

    public function novoJogo($id, $nome, $nomeImagem, $detalhesJogo) {
        return new Jogo($id, $nome, $nomeImagem, $detalhesJogo);
    }

    public function getJogos() {
        return $this->_dao->select();
    }

    public function getJogoById($id) {
        return $this->_dao->selectById($id);
    }
    
    public function getJogosLiberados($id) {
        return $this->_dao->selectByIdTurmasLiberados($id);
    }
    
    public function getJogosBloqueados($id) {
        return $this->_dao->selectByIdTurmasBloqueados($id);
    }
    
    public function pesquisarJogos($pesquisa) {
        return $this->_dao->selectPesquisa($pesquisa);
    }

    public static function getInstancia() {
        if (self::$_instanciaJogo === null) {
            self::$_instanciaJogo = new ControleJogo();
        }
        return self::$_instanciaJogo;
    }

}