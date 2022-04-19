<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/AlunoTurma.class.php';

/**
 * @author Daiane
 */
class AlunoTurmaDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(AlunoTurma $p) {

        $this->_config = ControleDatabase::getInstancia();

        try {
            session_start();

                $i = 0;
                while ($i < count($p->getAluno())) {
                    $sql = "INSERT INTO `alunos_turmas`(`alunos_id_aluno`, `turmas_id_turma`) VALUES (:alunos, :turmas)";

                    $query = $this->_config->connect()->prepare($sql);

                    $query->bindParam(':alunos', $p->getAluno()[$i], PDO::PARAM_INT);
                    $query->bindParam(':turmas', $p->getTurma(), PDO::PARAM_INT);

                    $status = $query->execute();

                    if (!$status) {
                        return false;
                    }
                    $i++;
                }
                return $status;
           
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

        $sql = "SELECT 
		alunos.id_aluno,
		usuarios.id_usuario,
		usuarios.nome_usuario,
		usuarios.email_usuario,
		turmas.id_turma,
		turmas.nome_turma
      FROM alunos_turmas

      LEFT JOIN turmas ON turmas.id_turma = alunos_turmas.turmas_id_turma
      LEFT JOIN alunos ON alunos.id_aluno = alunos_turmas.alunos_id_aluno 
      LEFT JOIN usuarios on alunos.usuarios_id_usuario = usuarios.id_usuario 
      WHERE alunos_turmas.turmas_id_turma = ? and alunos.estaAtivo_aluno = 1
      GROUP BY id_aluno
      ORDER BY nome_usuario";

        $query = $this->_config->connect()->prepare($sql);

        $query->bindParam(1, $id, PDO::PARAM_INT);

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
    
     public function selectByIdAluno($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
		id_aluno, id_turma, estaAberta_turma
                FROM alunos_turmas
                LEFT JOIN turmas ON alunos_turmas.turmas_id_turma = turmas.id_turma 
                LEFT JOIN alunos ON alunos.id_aluno = alunos_turmas.alunos_id_aluno 
                LEFT JOIN usuarios ON alunos.usuarios_id_usuario = usuarios.id_usuario
                WHERE usuarios.id_usuario = ? and alunos.estaAtivo_aluno = 1 and estaAberta_turma = 1";

        $query = $this->_config->connect()->prepare($sql);

        $query->bindParam(1, $id, PDO::PARAM_INT);

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

    public function deleteByIdTurma($idAluno, $idTurma) {
        $this->_config = ControleDatabase::getInstancia();
        try {


            $sql = "DELETE FROM `alunos_turmas` WHERE ";

            if (!$idTurma == null) {
                $sql .= "turmas_id_turma = :turma ";
            }

            if (!$idAluno == null) {
                 if (!$idTurma == null) {
                    $sql .= "and ";
                }
                $sql .= "alunos_id_aluno = :aluno ";
            }
            

            $query = $this->_config->connect()->prepare($sql);

            if (!$idTurma == null) {
                $query->bindParam(":turma", $idTurma, PDO::PARAM_INT);
            }

            if (!$idAluno == null) {
                $query->bindParam(":aluno", $idAluno, PDO::PARAM_INT);
            }

            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

}
