<?php
include_once '../controle/ControleJogo.class.php';
include_once '../controle/ControleJogosLiberadosTurma.class.php';
include_once '../controle/ControleAlunoTurma.class.php';
session_start();

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header('Location:index.html');
}

$idUser = $_SESSION['id_usuario'];
$tipoUser = $_SESSION['tipo_usuario'];
$nomeUser = $_SESSION['nome_usuario'];


if (isset($_SESSION['id_professor']) || !empty($_SESSION['id_professor'])) {
    $idProfessor = $_SESSION['id_professor'];
}

$campoInput = "";

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
        <meta charset="utf-8">
        <title>SENAI - Curso Técnico em Logística</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->


        <link rel="stylesheet" type="text/css" href="dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="dist/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="dist/css/geral.css">


    </head>

    <body>

        <header>
            <nav class="navbar navbar-fixed-top" role="navigation">
                <a class="navbar-brand" href="#"><img src="dist/img/logo.png" alt="SENAI BLUMENAU"/></a>
                <div class="container">
                    <div class="trapezoid-search">

                        <div class="input-group">
                            <input type="text" class="form-control" id="pesquisaTelasGrandes" placeholder="Pesquisar jogo..." <?php echo $campoInput ?>/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="pesquisar($('#pesquisaTelasGrandes').val())"><i class="glyphicon glyphicon-search"></i></button>
                            </span>
                        </div>
                    </div>

                    <div id="pesquisaTelasMenores">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputTelasMenores" placeholder="Pesquisar jogo..." <?php echo $campoInput ?>/> 
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="pesquisar($('#pesquisaTelasMenores input').val())"><i class="glyphicon glyphicon-search"></i></button>
                            </span>
                        </div>
                    </div>



                    <ul id="menu">
                        <li>Curso Técnico em Logística</li>
                        <li>

                            <?php
                            if ($tipoUser == 1) {
                                ?>
                                <span id="solicitacoesAcesso" class="glyphicon glyphicon-user" onclick="abrirGerenciamentoAcesso(null)"><span id="nAcesso">0</span></span> 

                                <?php
                            }
                            ?>

                            <span class="dropdown" id="menuDropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">
                                    <span id="nomeUser"><?php echo $nomeUser ?></span>
                                    <span class="caret"></span></button>
                                <ul id="dropMenu" class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                    <?php
                                    if ($tipoUser == 1 || $tipoUser == 2) {
                                        ?>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="jogos.php">Jogos</a></li>

                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="controle.php">Controle</a></li>
                                        <?php
                                    }
                                    if ($tipoUser == 1) {
                                        ?>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onclick="abrirGerenciamentoAcesso(null)">Permissões</a></li>

                                        <?php
                                    }
                                    ?>


                                    <li role="presentation"><a data-target=".modalEditarPerfil" data-toggle="modal" role="menuitem" tabindex="-1" href="#"  onclick="abrirModalEditarPerfil(<?php echo $idUser ?>, 'perfil', null, null)">Editar Perfil</a></li>



                                    <li role="presentation" class="divider"></li>



                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="logout.php">Sair</a></li>

                                </ul>
                            </span>



                        </li>
                    </ul>

                </div>
                <div id="menuTelaMenores">
                    <ul class="listaSemEstilo">
                        <li><a href="controle.php">Controle</a></li>
                        <li><a href="jogos.php">Jogos</a> </li>
                        <li><a href="#" onclick="abrirGerenciamentoAcesso()">Permissões</a></li>
                        <li><a data-target=".modalEditarPerfil" data-toggle="modal" role="menuitem" tabindex="-1" href="#"  onclick="abrirModalEditarPerfil(<?php echo $idUser ?>, 'perfil', null, null)">Editar Perfil</a></li>
                        <li><a href="logout.php">Sair</a></li>
                    </ul>
                </div>
            </nav>
            <div id="responsive" class="modal fade modalEditarPerfil" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="modalEditarPerfil">
                        Carregando...
                    </div>
                </div>
        </header>






