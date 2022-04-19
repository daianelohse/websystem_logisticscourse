<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/Professor.class.php';

class ProfessorDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(Professor $p) {


        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "INSERT IGNORE INTO `professores`(`id_professor`, `usuarios_id_usuario`, `estaAtivo_professor`) VALUES (:id, :usuario, :ativo)"
                    . "ON DUPLICATE KEY UPDATE estaAtivo_professor = :ativo";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':id', $p->getId(), PDO::PARAM_INT);
            $query->bindParam(':usuario', $p->getUsuario(), PDO::PARAM_INT);
            $query->bindParam(':ativo', $p->estaAtivo(), PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT *, nome_usuario as nome_professor from professores LEFT JOIN usuarios ON usuarios.id_usuario = professores.usuarios_id_usuario";

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

    public function selectByIdUser($id) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from professores where usuarios_id_usuario = ?";

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

        $sql = "SELECT *, nome_usuario as nome_professor "
                . "FROM professores "
                . "LEFT JOIN usuarios ON usuarios.id_usuario = professores.usuarios_id_usuario "
                . "WHERE nome_usuario LIKE ? and usuarios.tipo_usuario = 2 and professores.estaAtivo_professor = 1";

        $query = $this->_config->connect()->prepare($sql);

        $nome = "%" . $nome . "%";

        // Só se tiver values
        $query->bindParam(1, $nome, PDO::PARAM_STR);

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

    function update($id, $ativo) {
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE `professores` SET estaAtivo_professor = :ativo WHERE usuarios_id_usuario = :id";

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

?>