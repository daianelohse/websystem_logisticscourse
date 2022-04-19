<?php
include_once 'dao/RelatorioDao.class.php';
include_once '../modelo/Relatorio.class.php';
/**
 * @author Daiane
 */
class ControleRelatorio {

    private $_dao;
    private static $_instanciaRelatorio = null;

    private function __construct() {
        $this->_dao = new RelatorioDao();
    }

    public function novoRelatorio($idRelatorio, $idJogo, $idAluno, $idUsuario, $tempo, $data) {
        return new Relatorio($idRelatorio, $idJogo, $idAluno, $idUsuario, $tempo, $data);
    }
    
     public function insert(Relatorio $r) {
        return $this->_dao->insert($r);
    }
    
    public function getRelatorios() {
        return $this->_dao->select();
    }
    
    public function getRelatorioByIdAluno($id, $idTurmaRelatorio, $dataInicial, $dataFinal) {
        return $this->_dao->selectByIdAluno($id, $idTurmaRelatorio, $dataInicial, $dataFinal);
    }

    public function getRelatorioByIdTurma($id, $dataInicio, $dataFim) {
        return $this->_dao->selectByIdTurma($id, $dataInicio, $dataFim);
    }
    
    public function getVerificarRelatorioByIdTurma($id, $dataInicio, $dataFim) {
        return $this->_dao->selectVerificarByIdTurma($id, $dataInicio, $dataFim);
    }
    
    public function getVerificarRelatorioByIdAluno($id, $turma, $dataInicio, $dataFim) {
        return $this->_dao->selectVerificarByIdAluno($id, $turma, $dataInicio, $dataFim);
    }
    
     public function deleteByIdTurma($id) {
        return $this->_dao->deleteByIdTurma($id);
    }

    public static function getInstancia() {
        if (self::$_instanciaRelatorio === null) {
            self::$_instanciaRelatorio = new ControleRelatorio();
        }
        return self::$_instanciaRelatorio;
    }

}
