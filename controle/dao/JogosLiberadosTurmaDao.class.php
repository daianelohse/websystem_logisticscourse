<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/JogosLiberadosTurma.class.php';

/**
 * @author Daiane
 */
class JogosLiberadosTurmaDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(JogosLiberadosTurma $j) {


        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "INSERT INTO `jogos_liberados_turma`(`turmas_id_turma`, `jogos_id_jogo`) VALUES (:idTurma, :idJogo)";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':idTurma', $j->getIdTurma(), PDO::PARAM_INT);
            $query->bindParam(':idJogo', $j->getIdJogo(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function selectByIdTurmaJogo($idJogo, $idTurma) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from jogos_liberados_turma ";

        if (!$idTurma == null) {
            $sql .= "where turmas_id_turma = :turma";
        }

        if (!$idJogo == null) {
            if (!$idTurma == null) {
                $sql .= " and ";
            }
            $sql .= " jogos_id_jogo = :jogo";
        }

        $query = $this->_config->connect()->prepare($sql);

        if (!$idTurma == null) {
            $query->bindParam(":turma", $idTurma, PDO::PARAM_INT);
        }

        if (!$idJogo == null) {
            $query->bindParam(":jogo", $idJogo, PDO::PARAM_INT);
        }


        $query->execute();

        if (isset($class)) {
            $rs = $query->fetchAll(PDO::FETCH_CLASS, $class) or die(print_r($query->errorInfo(), true));
        } else {
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        try {
            return $rs;
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

    public function selectByUser($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM jogos_liberados_turma 
                 LEFT JOIN jogos ON jogos_liberados_turma.jogos_id_jogo = jogos.id_jogo
                 LEFT JOIN alunos_turmas ON jogos_liberados_turma.turmas_id_turma = alunos_turmas.turmas_id_turma
                 LEFT JOIN turmas ON jogos_liberados_turma.turmas_id_turma = turmas.id_turma
                 LEFT JOIN alunos ON alunos_turmas.alunos_id_aluno = alunos.id_aluno
                 WHERE turmas.estaAberta_turma = 1 and alunos.usuarios_id_usuario = ? and jogos_liberados_turma.estaAtivo_jogoTurma = 1
                 GROUP BY jogos.id_jogo
             ORDER BY jogos_id_jogo ASC";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
        $query->bindParam(1, $id, PDO::PARAM_INT);

        $query->execute();

        if (isset($class)) {
            $rs = $query->fetchAll(PDO::FETCH_CLASS, $class) or die(print_r($query->errorInfo(), true));
        } else {
            $rs = $query->fetchAll(PDO::FETCH_ASSOC);
        }

        try {
            return $rs;
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

    public function update($jogo, $turma, $estaAtivo) {
        $this->_config = ControleDatabase::getInstancia();
        try {


            $sql = "UPDATE jogos_liberados_turma SET estaAtivo_jogoTurma = :ativo WHERE 
                turmas_id_turma = :turma and jogos_id_jogo = :jogo";


            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(":ativo", $estaAtivo, PDO::PARAM_INT);
            if (!$turma == null) {
                $query->bindParam(":turma", $turma, PDO::PARAM_INT);
            }
            if (!$jogo == null) {
                $query->bindParam(":jogo", $jogo, PDO::PARAM_INT);
            }


            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

    public function delete($turma) {
        $this->_config = ControleDatabase::getInstancia();
        try {


            $sql = "DELETE FROM jogos_liberados_turma  WHERE turmas_id_turma = :turma ";


            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(":turma", $turma, PDO::PARAM_INT);


            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

}
