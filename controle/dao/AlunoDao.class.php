<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/Aluno.class.php';

class AlunoDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(Aluno $a) {

        $this->_config = ControleDatabase::getInstancia();

        try {

            $sql = "INSERT INTO `alunos`(`id_aluno`, `usuarios_id_usuario`, `estaAtivo_aluno`) VALUES (:id, :usuario, :ativo)"
                    . "ON DUPLICATE KEY UPDATE estaAtivo_aluno = :ativo";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':id', $a->getId(), PDO::PARAM_INT);
            $query->bindParam(':usuario', $a->getUsuario(), PDO::PARAM_INT);
            $query->bindParam(':ativo', $a->estaAtivo(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from alunos LEFT JOIN usuarios ON usuarios.id_usuario = alunos.usuarios_id_usuario";

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

    public function selectById($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from professores where id_professor = ?";

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

    public function selectByIdTurma($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
		alunos.id_aluno,
		u1.id_usuario,
		u1.nome_usuario,
		u1.email_usuario,
                COUNT(u1.nome_usuario) as num_alunos,
		GROUP_CONCAT(u2.nome_usuario ORDER BY u2.nome_usuario ASC SEPARATOR ', ') as nome_professor,
		turmas.id_turma,
		turmas.nome_turma
            FROM professores_turmas

            LEFT JOIN turmas ON turmas.id_turma = professores_turmas.turmas_id_turma
            LEFT JOIN alunos ON alunos.turmas_id_turma = professores_turmas.turmas_id_turma 
            LEFT JOIN usuarios u1 on alunos.usuarios_id_usuario = u1.id_usuario 
            LEFT JOIN professores ON professores_turmas.professores_id_professor = professores.id_professor
            WHERE professores_turmas.turmas_id_turma =  ?
            GROUP BY id_aluno
            ORDER BY nome_usuario";

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

    public function selectByNome($nome, $id) {
        $this->_config = ControleDatabase::getInstancia();


        $sql = "SELECT id_aluno, id_usuario, nome_usuario, email_usuario FROM alunos 
                LEFT JOIN usuarios ON usuarios.id_usuario = alunos.usuarios_id_usuario
                WHERE alunos.estaAtivo_aluno = 1 and
              alunos.id_aluno NOT IN (SELECT alunos_id_aluno FROM alunos_turmas WHERE turmas_id_turma = :id) and nome_usuario LIKE :nome";


        $query = $this->_config->connect()->prepare($sql);

        $pesquisa = "%" . $nome . "%";
        // Só se tiver values
        $query->bindParam(":nome", $pesquisa, PDO::PARAM_STR);
        $query->bindParam(":id", $id, PDO::PARAM_INT);

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

    public function selectByIdUser($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM alunos "
                . "LEFT JOIN alunos_turmas ON alunos.id_aluno = alunos_turmas.alunos_id_aluno "
                . "LEFT JOIN turmas ON alunos_turmas.turmas_id_turma = turmas.id_turma "
                . "WHERE usuarios_id_usuario = ? and turmas.estaAberta_turma = 1";

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

    function deleteByIdTurma($id) {
        $this->_config = ControleDatabase::getInstancia();
        try {

            $sql = "DELETE FROM `alunos` WHERE turmas_id_turma = :id";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(":id", $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

    function update($id, $ativo) {
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE `alunos` SET estaAtivo_aluno = :ativo WHERE usuarios_id_usuario = :id";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->bindParam(':ativo', $ativo, PDO::PARAM_INT);

            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

}
