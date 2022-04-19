<?php

include_once '../controle/ControleDatabase.class.php';

class JogoDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(Jogo $jogo) {
        $_conexao = ControleDatabase::getInstancia();

        $_conexao->query("INSERT INTO jogo VALUES(
				" . $apostador->getId() . "," . "'" . $apostador->getCreditos() . "'," . "'" . $apostador->getDadosConta() . "'" . "'1)");
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from jogos";

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

        $sql = "SELECT * from jogos where id_jogo = ?";

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

    public function selectByIdTurmasLiberados($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM jogos_liberados_turma 
                LEFT JOIN jogos ON jogos_liberados_turma.jogos_id_jogo = jogos.id_jogo
                WHERE jogos_liberados_turma.turmas_id_turma = ? and jogos_liberados_turma.estaAtivo_jogoTurma = 1
                 GROUP BY jogos.id_jogo
                ORDER BY jogos.id_jogo ASC";
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

    public function selectByIdTurmasBloqueados($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM jogos
                LEFT JOIN jogos_liberados_turma ON jogos.id_jogo = jogos_liberados_turma.jogos_id_jogo
                WHERE jogos.id_jogo 
                NOT IN 
                (SELECT jogos_liberados_turma.jogos_id_jogo 
                        FROM jogos_liberados_turma WHERE jogos_liberados_turma.turmas_id_turma = :id)
                or (jogos_liberados_turma.turmas_id_turma = :id and jogos_liberados_turma.estaAtivo_jogoTurma = 0)
                 GROUP BY jogos.id_jogo
                 ORDER BY jogos.id_jogo ASC";

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

    public function selectPesquisa($pesquisa) {
        $this->_config = ControleDatabase::getInstancia();



        $sql = "SELECT * FROM jogos WHERE nome_jogo LIKE :pesquisa or tags_jogo LIKE :pesquisa";


        $query = $this->_config->connect()->prepare($sql);

        $pesquisa = "%" . $pesquisa . "%";

        // Só se tiver values
        $query->bindParam(":pesquisa", $pesquisa, PDO::PARAM_STR);


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

}

?>