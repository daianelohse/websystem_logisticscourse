<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/ProfessorTurma.class.php';

/**
 * @author Daiane
 */
class ProfessorTurmaDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(ProfessorTurma $p) {
  
        $this->_config = ControleDatabase::getInstancia();

        try {
            session_start();

            if ($_SESSION['tipo_usuario'] == '1' || $_SESSION['tipo_usuario'] == 1) {

                $i = 0;
                while ($i < count($p->getProfessor())) {
                    $sql = "INSERT INTO `professores_turmas`(`professores_id_professor`, `turmas_id_turma`) VALUES (:professores, :turmas)";

                    $query = $this->_config->connect()->prepare($sql);

                    $query->bindParam(':professores', $p->getProfessor()[$i], PDO::PARAM_INT);
                    $query->bindParam(':turmas', $p->getTurma(), PDO::PARAM_INT);

                    $status = $query->execute();

                    if (!$status) {
                        return false;
                    }
                    $i++;
                }
                return $status;
            } else {
                $sql = "INSERT INTO `professores_turmas`(`professores_id_professor`, `turmas_id_turma`) VALUES (:professores, :turmas)";

                $query = $this->_config->connect()->prepare($sql);

                $query->bindParam(':professores', $p->getProfessor(), PDO::PARAM_INT);
                $query->bindParam(':turmas', $p->getTurma(), PDO::PARAM_INT);

                return $query->execute();
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT turmas_id_turma as id_turma, id_professor, nome_usuario FROM professores_turmas 
                LEFT JOIN professores ON professores_turmas.professores_id_professor = professores.id_professor
                LEFT JOIN usuarios ON professores.usuarios_id_usuario = usuarios.id_usuario";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
        $parametros = null;
        $query->execute($parametros);

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

    public function selectByIdTurma($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT id_professor, estaAtivo_professor,
                usuarios.nome_usuario,
                turmas.nome_turma
                FROM professores_turmas
                LEFT JOIN professores ON professores_turmas.professores_id_professor = professores.id_professor
                LEFT JOIN usuarios ON professores.usuarios_id_usuario = usuarios.id_usuario
                     LEFT JOIN turmas ON turmas.id_turma = professores_turmas.turmas_id_turma
                WHERE turmas_id_turma = ? ORDER BY usuarios.nome_usuario ASC ";

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

    public function selectByIdTurmaEdicao($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT id_professor, 
                nome_usuario as nome_professor
                FROM professores_turmas 
                LEFT JOIN professores ON professores_turmas.professores_id_professor = professores.id_professor
                LEFT JOIN usuarios ON professores.usuarios_id_usuario = usuarios.id_usuario
                WHERE turmas_id_turma = ?";

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

    public function delete($id) {

        $this->_config = ControleDatabase::getInstancia();

        try {

            $sql = "DELETE FROM professores_turmas WHERE turmas_id_turma = :id";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':id', $id, PDO::PARAM_INT);

            return $query->execute();
            
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

}
