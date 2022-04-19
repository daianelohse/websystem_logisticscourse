<?php

include_once '../controle/ControleDatabase.class.php';

/*
 * @author Daiane
 */

class RelatorioDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(Relatorio $r) {
        
        
        

        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "INSERT INTO `jogos_alunos`(`jogos_id_jogo`, `alunos_id_aluno`, `alunos_usuarios_id_usuario`, "
                    . "`tempo_jogo`, `data_jogo`) VALUES (:jogo, :aluno, :usuario,:tempo, now())";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':jogo', $r->getIdJogo(), PDO::PARAM_INT);
            $query->bindParam(':aluno', $r->getIdAluno(), PDO::PARAM_INT);
            $query->bindParam(':usuario', $r->getIdUsuario(), PDO::PARAM_INT);
            $query->bindParam(':tempo', $r->getTempo(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT *,GROUP_CONCAT(DISTINCT jogos.nome_jogo ORDER BY jogos_alunos.data_jogo DESC) as 'mais_jogados',
                            SEC_TO_TIME( SUM( TIME_TO_SEC(jogos_alunos.tempo_jogo) ) ) AS total_time

                    FROM jogos_alunos
                    LEFT JOIN jogos ON jogos_id_jogo = jogos.id_jogo 
                    LEFT JOIN usuarios ON alunos_usuarios_id_usuario = usuarios.id_usuario
                    LEFT JOIN turmas ON alunos_turmas_id_turma = turmas.id_turma
                    GROUP BY alunos_id_aluno";

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

    public function selectByIdTurma($id, $dataInicio, $dataFim) {


        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
                    usuarios.id_usuario,
                    usuarios.nome_usuario,
                    usuarios.email_usuario,
                    jogos_alunos.jogos_id_jogo as id_jogo, 
                    alunos_usuarios_id_usuario as id_aluno, 
                    jogos.nome_jogo,
                    jogos_liberados_turma.turmas_id_turma,
                    alunos.estaAtivo_aluno,
                    jogos_alunos.data_jogo,
                    SEC_TO_TIME( SUM( TIME_TO_SEC(jogos_alunos.tempo_jogo) ) ) AS total_time
                  FROM jogos_alunos
                    LEFT JOIN jogos ON jogos_alunos.jogos_id_jogo = jogos.id_jogo 
                    LEFT JOIN usuarios on jogos_alunos.alunos_usuarios_id_usuario = usuarios.id_usuario
                    LEFT JOIN alunos ON jogos_alunos.alunos_id_aluno = alunos.id_aluno
                     LEFT JOIN alunos_turmas ON jogos_alunos.alunos_id_aluno = alunos_turmas.alunos_id_aluno
                    LEFT JOIN jogos_liberados_turma ON jogos.id_jogo = jogos_liberados_turma.jogos_id_jogo
                WHERE jogos_liberados_turma.turmas_id_turma = :id and alunos_turmas.turmas_id_turma = :id and alunos.estaAtivo_aluno = 1 
                and jogos_alunos.data_jogo >= :datainicio and jogos_alunos.data_jogo <= :datafim
                GROUP BY alunos_usuarios_id_usuario, jogos_alunos.jogos_id_jogo";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':datainicio', $dataInicio, PDO::PARAM_STR);
        $query->bindParam(':datafim', $dataFim, PDO::PARAM_STR);

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
    
    public function selectVerificarByIdTurma($id, $dataInicio, $dataFim) {
        


        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT  jogos_alunos.alunos_id_aluno
                FROM jogos_alunos
                LEFT JOIN alunos ON jogos_alunos.alunos_id_aluno = alunos.id_aluno
                LEFT JOIN alunos_turmas ON jogos_alunos.alunos_id_aluno = alunos_turmas.alunos_id_aluno
                LEFT JOIN jogos_liberados_turma ON jogos_alunos.jogos_id_jogo = jogos_liberados_turma.jogos_id_jogo
                WHERE jogos_liberados_turma.turmas_id_turma = :id and alunos_turmas.turmas_id_turma = :id and alunos.estaAtivo_aluno = 1 and jogos_alunos.data_jogo >= :datainicio and jogos_alunos.data_jogo <= :datafim
                GROUP BY alunos_usuarios_id_usuario, jogos_alunos.jogos_id_jogo";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':datainicio', $dataInicio, PDO::PARAM_STR);
        $query->bindParam(':datafim', $dataFim, PDO::PARAM_STR);

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
    
     public function selectVerificarByIdAluno($id, $turma,$dataInicio, $dataFim) {
         


        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT  jogos_alunos.alunos_id_aluno
                 FROM jogos_alunos
                 LEFT JOIN alunos ON jogos_alunos.alunos_id_aluno = alunos.id_aluno
                 LEFT JOIN jogos_liberados_turma ON jogos_alunos.jogos_id_jogo = jogos_liberados_turma.jogos_id_jogo
                WHERE alunos_id_aluno = :id and jogos_liberados_turma.turmas_id_turma = :turma and alunos.estaAtivo_aluno = 1 
                and jogos_alunos.data_jogo >= :datainicio and jogos_alunos.data_jogo <= :datafim
                GROUP BY alunos_usuarios_id_usuario, jogos_alunos.jogos_id_jogo";

        $query = $this->_config->connect()->prepare($sql);

        // Só se tiver values
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':turma', $turma, PDO::PARAM_STR);
        $query->bindParam(':datainicio', $dataInicio, PDO::PARAM_STR);
        $query->bindParam(':datafim', $dataFim, PDO::PARAM_STR);

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

    public function selectByIdAluno($id, $idTurmaRelatorio, $dataInicial, $dataFinal) {
        
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT 
                    u1.id_usuario,
                    u1.nome_usuario,
                    u1.email_usuario,
                    jogos_alunos.jogos_id_jogo as id_jogo, 
                    alunos_usuarios_id_usuario as id_aluno, 
                    jogos.nome_jogo,
                    jogos_alunos.data_jogo,
                    SEC_TO_TIME( SUM( TIME_TO_SEC(jogos_alunos.tempo_jogo) ) ) AS total_time
                    FROM jogos_alunos
                    LEFT JOIN jogos ON jogos_alunos.jogos_id_jogo = jogos.id_jogo 
                    LEFT JOIN usuarios u1 on jogos_alunos.alunos_usuarios_id_usuario = u1.id_usuario 
                    LEFT JOIN alunos on alunos.id_aluno = jogos_alunos.alunos_id_aluno 
                    LEFT JOIN jogos_liberados_turma on jogos_alunos.jogos_id_jogo = jogos_liberados_turma.jogos_id_jogo
                 WHERE jogos_alunos.alunos_id_aluno = :aluno and alunos.estaAtivo_aluno = 1 and 
                 jogos_liberados_turma.turmas_id_turma = :turma and
                 jogos_alunos.data_jogo >= :datainicio and jogos_alunos.data_jogo <= :datafim
                GROUP BY alunos_usuarios_id_usuario, jogos_alunos.jogos_id_jogo
                ORDER BY data_jogo DESC";

        $query = $this->_config->connect()->prepare($sql);
        
        $query->bindParam(":aluno", $id, PDO::PARAM_INT);
        $query->bindParam(":datainicio", $dataInicial, PDO::PARAM_STR);
        $query->bindParam(":datafim", $dataFinal, PDO::PARAM_STR);
        $query->bindParam(":turma", $idTurmaRelatorio, PDO::PARAM_INT);

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

            $sql = "DELETE FROM `jogos_alunos` WHERE alunos_turmas_id_turma = :id";

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
