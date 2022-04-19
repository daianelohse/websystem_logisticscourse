<?php

include_once '../controle/ControleDatabase.class.php';
include_once '../modelo/Usuario.class.php';

class UsuarioDao {

    private $_conexao = null;

    public function __construct() {
        
    }

    // CRUD
    public function insert(Usuario $u) {
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "INSERT INTO `usuarios`(`id_usuario`, `nome_usuario`, `user_usuario`, `email_usuario`, `senha_usuario`, 
				 `tipo_usuario`, `estaAprovado_usuario`) 
                                 VALUES (:id, :nome, :user, :email, :senha, :tipoUsuario, :estaAprovado)";

            $query = $this->_config->connect()->prepare($sql);



            $estaAprovado = false;

            $query->bindParam(':id', $u->getId(), PDO::PARAM_INT);
            $query->bindParam(':nome', $u->getNome(), PDO::PARAM_STR);
            $query->bindParam(':user', $u->getUser(), PDO::PARAM_STR);
            $query->bindParam(':email', $u->getEmail(), PDO::PARAM_STR);
            $query->bindParam(':senha', $u->getSenha(), PDO::PARAM_STR);
            $query->bindParam(':tipoUsuario', $u->getTipoUsuario(), PDO::PARAM_INT);
            $query->bindParam(':estaAprovado', $estaAprovado, PDO::PARAM_BOOL);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function update(Usuario $u) {
        
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE `usuarios` SET 
                 `nome_usuario` = :nome, 
                `user_usuario` = :user, 
                `email_usuario` = :email";

            if (!$u->getSenha() == null || !$u->getSenha() == "") {
                $sql .= " ,`senha_usuario` = :senha WHERE id_usuario = :id";
            } else {
                $sql .= " WHERE id_usuario = :id";
            }
            

            $query = $this->_config->connect()->prepare($sql);


            $query->bindParam(':id', $u->getId(), PDO::PARAM_INT);
            $query->bindParam(':nome', $u->getNome(), PDO::PARAM_STR);
            $query->bindParam(':user', $u->getUser(), PDO::PARAM_STR);
            $query->bindParam(':email', $u->getEmail(), PDO::PARAM_STR);
            if (!$u->getSenha() == null || !$u->getSenha() == "") {
                $query->bindParam(':senha', md5($u->getSenha()), PDO::PARAM_STR);
            }
            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function setPermissaoUsuario($id, $tipo) {
        $this->_config = ControleDatabase::getInstancia();

        try {
            $sql = "UPDATE usuarios
                    SET tipo_usuario = :tipo, estaAprovado_usuario = :aprovado
                    WHERE id_usuario = :id;";

            $query = $this->_config->connect()->prepare($sql);

            $aprovado = true;
            $query->bindParam(':tipo', $tipo, PDO::PARAM_INT);
            $query->bindParam(':aprovado', $aprovado, PDO::PARAM_BOOL);
            $query->bindParam(':id', $id, PDO::PARAM_INT);

            return $query->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function select() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * from usuarios WHERE id_usuario != 1";

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

        $sql = "SELECT * from usuarios where id_usuario = ?";

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

    public function selectByUser($id, $user) {

        $this->_config = ControleDatabase::getInstancia();
        try {

            if ($id == null || $id == '') {
                $sql = "SELECT * from usuarios where user_usuario = :user";

                $query = $this->_config->connect()->prepare($sql);

                $query->bindParam(':user', $user, PDO::PARAM_STR);
            } else {
                $sql = "SELECT * from usuarios where user_usuario = :user and id_usuario != :id";

                $query = $this->_config->connect()->prepare($sql);

                $query->bindParam(':user', $user, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_INT);
            }

            $query->execute();

            $rs = null;


            if (isset($class)) {
                $rs = $query->fetchAll(PDO::FETCH_CLASS, $class) or die(print_r($query->errorInfo(), true));
            } else {
                $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            }
            return $rs;
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function selectByEmail($id, $email) {
        $this->_config = ControleDatabase::getInstancia();
        try {

            if ($id == null || $id == '') {
                $sql = "SELECT * from usuarios where email_usuario = :email";

                $query = $this->_config->connect()->prepare($sql);

                $query->bindParam(':email', $email, PDO::PARAM_STR);
            } else {
                $sql = "SELECT * from usuarios where email_usuario = :email and id_usuario != :id";

                $query = $this->_config->connect()->prepare($sql);

                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':id', $id, PDO::PARAM_INT);
            }

            $query->execute();

            $rs = null;


            if (isset($class)) {
                $rs = $query->fetchAll(PDO::FETCH_CLASS, $class) or die(print_r($query->errorInfo(), true));
            } else {
                $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            }
            return $rs;
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function selectByEmailUser($emailUser) {
        $this->_config = ControleDatabase::getInstancia();
        try {
            $sql = "SELECT * from usuarios where email_usuario = :string or user_usuario = :string";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':string', $emailUser, PDO::PARAM_STR);


            $query->execute();

            if (isset($class)) {
                $rs = $query->fetchAll(PDO::FETCH_CLASS, $class) or die(print_r($query->errorInfo(), true));
            } else {
                $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            }
            return $rs;
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function selectValidarLogin($user, $senha) {



        $this->_config = ControleDatabase::getInstancia();
        try {
            $sql = "SELECT usuarios.id_usuario, 
                    usuarios.nome_usuario, 
                    usuarios.user_usuario, 
                    usuarios.tipo_usuario, 
                    usuarios.estaAprovado_usuario,
                    professores.estaAtivo_professor,
                    alunos.estaAtivo_aluno
                    FROM usuarios 
                    LEFT JOIN alunos ON alunos.usuarios_id_usuario = usuarios.id_usuario 
                    LEFT JOIN professores ON professores.usuarios_id_usuario = usuarios.id_usuario
                    WHERE (user_usuario = :user or email_usuario = :email) and senha_usuario = :senha";

            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':user', $user, PDO::PARAM_STR);
            $query->bindParam(':email', $user, PDO::PARAM_STR);
            $query->bindParam(':senha', $senha, PDO::PARAM_STR);

            $query->execute();

            $rs = null;


            if (isset($class)) {
                $rs = $query->fetchAll(PDO::FETCH_CLASS, $class) or die(print_r($query->errorInfo(), true));
            } else {
                $rs = $query->fetchAll(PDO::FETCH_ASSOC);
            }

            return $rs;
        } catch (PDOException $e) {
            return $e->getMessage();
        } finally {
            $this->_config->__destruct();
        }
    }

    public function getUsuariosComPermissaoAlunoSemTurma() {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM usuarios WHERE tipo_usuario = 3 and estaAprovado_usuario = true and id_usuario NOT IN (SELECT usuarios_id_usuario FROM alunos)";

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

    public function selectComPermissao($pesquisa) {

        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM usuarios 
            LEFT JOIN alunos ON usuarios.id_usuario = alunos.usuarios_id_usuario
            LEFT JOIN professores ON usuarios.id_usuario = professores.usuarios_id_usuario
            WHERE tipo_usuario != 1 and (alunos.estaAtivo_aluno = true OR professores.estaAtivo_professor = true)";

        if (!$pesquisa == null) {
            $sql .= " and (usuarios.nome_usuario LIKE :pesquisa or usuarios.user_usuario LIKE :pesquisa)";
        }
        $sql .= " ORDER BY nome_usuario ASC";

        $query = $this->_config->connect()->prepare($sql);


        if (!$pesquisa == null) {
            $pesquisa = "%" . $pesquisa . "%";
            $query->bindParam(':pesquisa', $pesquisa, PDO::PARAM_STR);
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

    public function getUsuariosSemPermissao($pesquisa) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM usuarios WHERE estaAprovado_usuario = false and tipo_usuario != 1";
        if (!$pesquisa == null) {
            $sql .= " and (usuarios.nome_usuario LIKE :pesquisa or usuarios.user_usuario LIKE :pesquisa)";
        }
        $sql .= " ORDER BY nome_usuario ASC";

        $query = $this->_config->connect()->prepare($sql);

        if (!$pesquisa == null) {
            $pesquisa = "%" . $pesquisa . "%";
            $query->bindParam(':pesquisa', $pesquisa, PDO::PARAM_STR);
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

    public function getInativos($pesquisa) {
        $this->_config = ControleDatabase::getInstancia();

        $sql = "SELECT * FROM usuarios 
                LEFT JOIN alunos ON usuarios.id_usuario = alunos.usuarios_id_usuario
                LEFT JOIN professores ON usuarios.id_usuario = professores.usuarios_id_usuario
                WHERE estaAprovado_usuario = 1 and tipo_usuario != 1 and
                ((estaAtivo_professor = 0 or estaAtivo_professor is null) and (estaAtivo_aluno = 0 or estaAtivo_aluno is null))
                ";

        if (!$pesquisa == null) {
            $sql .= " and (usuarios.nome_usuario LIKE :pesquisa or usuarios.user_usuario LIKE :pesquisa)";
        }
        $sql .= " ORDER BY nome_usuario ASC";

        $query = $this->_config->connect()->prepare($sql);

        if (!$pesquisa == null) {
            $pesquisa = "%" . $pesquisa . "%";
            $query->bindParam(':pesquisa', $pesquisa, PDO::PARAM_STR);
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

    public function deleteById($id) {
        $this->_config = ControleDatabase::getInstancia();

        try {

            $sql = "DELETE FROM `usuarios` WHERE id_usuario = :id";


            $query = $this->_config->connect()->prepare($sql);

            $query->bindParam(':id', $id, PDO::PARAM_STR);

            return $query->execute();
        } catch (Exception $e) {
            return false;
        } finally {
            $this->_config->__destruct();
        }
    }

}

?>