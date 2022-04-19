<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/Turma.class.php';

class TurmaDao {

    private $_config = null;
    private $_ultimoId;

    public function __construct() {
        
    }

    // CRUD
    public function insert(Turma $t) {


        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "INSERT INTO `turmas`(`id_turma`, `nome_turma`) 
				VALUES (:turma, :nome)";

            $conexao = $this->_config->connect();

            $query = $conexao->prepare($sql);


            $query->bindParam(':turma', $t->getId(), PDO::PARAM_INT);
            $query->bindParam(':nome', $t->getNome(), PDO::PARAM_STR);

            $exe = $query->execute();

            if ($exe) {
                $this->_ultimoId = $conexao->lastInsertId();
                return $exe;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function update($id, $nome) {

        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE `turmas` SET  `nome_turma` = :nome where id_turma = :id";

            $conexao = $this->_config->connect();

            $query = $conexao->prepare($sql);

            $query->bindParam(':nome', $nome, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function arquivar($id) {
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE `turmas` SET `estaAberta_turma` = 0 WHERE `id_turma` = :id";

            $conexao = $this->_config->connect();

            $query = $conexao->prepare($sql);

            $query->bindParam(':id', $id, PDO::PARAM_INT);


            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function abrirTurma($id) {
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE `turmas` SET `estaAberta_turma` = 1 WHERE `id_turma` = :id";

            $conexao = $this->_config->connect();

            $query = $conexao->prepare($sql);

            $query->bindParam(':id', $id, PDO::PARAM_INT);


            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    function getlastInsertId() {
        return $this->_ultimoId;
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
                turmas.id_turma, 
                sum(case when alunos_turmas.turmas_id_turma is null or alunos.estaAtivo_aluno = 0 then 0 else 1 end) numero_alunos,
                turmas.nome_turma
                FROM turmas 
                LEFT JOIN alunos_turmas ON alunos_turmas.turmas_id_turma = turmas.id_turma
                LEFT JOIN alunos ON alunos_turmas.alunos_id_aluno = alunos.id_aluno
                WHERE `estaAberta_turma` = 1
                GROUP BY turmas.id_turma";

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

    public function selectByIdSemEsteProfessor($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 	t.id_turma, 
                        t.nome_turma
                FROM    turmas t
                WHERE   t.estaAberta_turma = 1 
                and     t.id_turma not in ( select pp.turmas_id_turma 
                                            from 	professores_turmas pp
                                            where 	pp.professores_id_professor = ?)";

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

    public function selectById($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from turmas where id_turma = ?";

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

    public function selectByIdProfessor($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
                turmas.id_turma, 
                sum(case when alunos_turmas.turmas_id_turma is null then 0 else 1 end) numero_alunos,
                turmas.nome_turma
                FROM turmas 
                LEFT JOIN alunos_turmas ON alunos_turmas.turmas_id_turma = turmas.id_turma
                LEFT JOIN professores_turmas ON turmas.id_turma = professores_turmas.turmas_id_turma
                WHERE `estaAberta_turma` = 1 and professores_turmas.professores_id_professor = ?
                GROUP BY turmas.id_turma";

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

    public function selectByNome($nome) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * 
                FROM turmas
                WHERE turmas.nome_turma = :turma";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
        $query->bindParam(":turma", $nome, PDO::PARAM_STR);

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

    public function selectTurmasArquivadas() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
                turmas.id_turma, 
                sum(case when alunos_turmas.turmas_id_turma is null or alunos.estaAtivo_aluno = 0 then 0 else 1 end) numero_alunos,
                turmas.nome_turma
                FROM turmas 
                LEFT JOIN alunos_turmas ON alunos_turmas.turmas_id_turma = turmas.id_turma
                LEFT JOIN alunos ON alunos_turmas.alunos_id_aluno = alunos.id_aluno
                WHERE `estaAberta_turma` = 0
                GROUP BY turmas.id_turma";

        $query = $this->_config->connect()->prepare($sql);


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

    public function selectTurmasArquivadasByProfessor($id) {
        $this->_config = ControleDatabase::getInstancia();
        
        

        $sql = "SELECT turmas.id_turma, 
                turmas.nome_turma,
                sum(case when alunos_turmas.turmas_id_turma is null or alunos.estaAtivo_aluno = 0 then 0 else 1 end) numero_alunos
                FROM turmas 
                LEFT JOIN alunos_turmas ON alunos_turmas.turmas_id_turma = turmas.id_turma
                LEFT JOIN professores_turmas ON professores_turmas.turmas_id_turma = turmas.id_turma
                 LEFT JOIN alunos ON alunos_turmas.alunos_id_aluno = alunos.id_aluno
                WHERE `estaAberta_turma` = 0 and  professores_turmas.professores_id_professor = :id
                GROUP BY turmas.id_turma";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
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

    public function numeroTurmasArquivadas() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT count(`id_turma`) as numeroTurmasArquivadas from turmas where `estaAberta_turma` = 0";

        $query = $this->_config->connect()->prepare($sql);

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

    function numeroTurmasArquivadasByProfessor($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT count(`id_turma`) as numeroTurmasArquivadas from turmas 
            LEFT JOIN professores_turmas ON turmas.id_turma = professores_turmas.turmas_id_turma
            where `estaAberta_turma` = 0 and professores_turmas.professores_id_professor = :id";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
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

    function delete($id) {
        
        $this->_config = ControleDatabase::getInstancia();
        try {

            $sql = "DELETE FROM `turmas` WHERE id_turma = :id";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(":id", $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

}

?>