<?php
    include 'cabecalho.php';
?>

<br style="clear: both" />

<div class="centralizar">

    <input type="hidden" name="tipo_user" id="tipoUser" value="<?php echo $tipoUser ?>" />
    <input type="hidden" name="id_user" id="idUser" value="<?php echo $idUser ?>" />

    <div id="controle">
        <div id="controle">
            <?php
            if ($tipoUser == 1) {
                include 'gerenciarPermissoes.html';
            }
            ?>
        </div>
    </div>


    <section id="jogos">
        <ol class="breadcrumb" style="display:none">
            <li></li>
        </ol>

        <div class="pagina">

            <div id="thumbs">

                <div class="row">



                    <?php
                    if ($_SESSION['tipo_usuario'] == 3) {
                        $jogos = ControleJogosLiberadosTurma::getInstancia();

                        $todosJogos = $jogos->getJogoLiberadoByUser($idUser);
                    } else if ($tipoUser == 1 || $tipoUser == 2) {
                        $jogos = ControleJogo::getInstancia();
                        $todosJogos = $jogos->getJogos();
                    }
                    
                    if (empty($todosJogos)) {
                        if ($_SESSION['tipo_usuario'] == 3) {
                            $controleAlunoTurma = ControleAlunoTurma::getInstancia();
                            
                            $res = $controleAlunoTurma->getAlunoTurmaByIdAluno($idUser);
                            
                            if (count($res) <=  0) {
                                echo '<p class="mensagemAlertaJogos"> Oops... Nenhum jogo está liberado para você, pois seu usuário não está cadastrado em nenhuma turma.<br />
                                            Peça para seu professor cadastrar você em sua turma.
                                            <strong>;)</strong><p>';
                            } else {
                                echo '<p class="mensagemAlertaJogos"> Nenhum jogo está liberado para sua turma. <strong>=(</strong>
                                    <p>';
                            }
                        } else {
                            echo '<p class="mensagemAlertaJogos"> Nenhum jogo encontrado. <strong>=(</strong>
                                    <p>';
                        }
                    } else {
                        for ($i = 0; $i < count($todosJogos); $i++) {
                            ?>

                            <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                <div class="thumbnail" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="abrirJogo(<?php echo $todosJogos[$i]['id_jogo'] ?>)">
                                    <img src="dist/img/jogos/<?php echo $todosJogos[$i]['nome_imagem_jogo'] ?>" class="img-responsive" alt=<?php echo '"' . utf8_encode($todosJogos[$i]['nome_jogo']) . '"' ?> />
                                    <span id="hoverJogos"><i class="playJogo glyphicon glyphicon-play-circle"></i> <br/><?php echo utf8_encode($todosJogos[$i]['nome_jogo']) ?></span>
                                </div>
                            </div>

                            <?php
                        }
                    }
                    ?>			
                </div>

            </div>
        </div>
       <div id="responsive">
        <div id="modalTodosJogos" class="modal fade bs-example-modal-lg" tabindex="-1"
             role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-lg">
                <div class="modal-content" id="modalJogo">

                </div>
            </div>
        </div>
         </div>

    </section>


</div>
<?php include 'rodape.html'; ?>