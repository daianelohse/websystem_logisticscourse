<?php
include_once '../controle/ControleJogo.class.php';

$id = $_GET ['url'];

if ($id !== null) {

    $jogos = ControleJogo::getInstancia();

    $jogoClicado = $jogos->getJogoById($id);

    if (empty($jogoClicado)) {
        echo 'Nenhum jogo encontrado! Consulte o administrador.';
    } else {

        $nomeJogo = $jogoClicado [0] ['nome_jogo'];
        $descricaoJogo = $jogoClicado [0] ['detalhes_jogo'];
    }
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"
                aria-hidden="true">&times;</button>
        <h3 class="modal-title"><?php echo utf8_encode($nomeJogo) ?></h3>
    </div>
    <div class="modal-body">
        <input type="hidden" name="idJogo" id="idJogo" value="<?php echo $id ?>"/>

        <?php
        switch ($id) {

            case '1' :
            case '2' :
            case '3' :
            case '4' :
                ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                            width="100%" height="100%"
                            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0">
                        <param name="movie" value="jogos/<?php echo $id ?>.swf" />
                        <embed width="100%" height="100%" name="plugin" src="jogos/<?php echo $id ?>.swf" type="application/x-shockwave-flash">
                        </embed>
                    </object>
                </div>

                <?php
                break;
            case '17':
                include "jogos/" . $id . ".php";
               

               echo    '<div id="areaRespostaJojo17"><button type="button" id="botaoAbreResposta" class="btn btn-info" onclick="verRespostaJogo(17)" style="display:none">VER RESPOSTA</button> <br /><div id="respostaNaviosNoPorto" style="display:none">'
                . '<img alt="Navios no porto - respostas" />'
                           . '</div></div>';
                

                break;
            default :
                include "jogos/" . $id . ".html";
        }
        ?>
    </div>
    <div class="modal-footer">
        <p>
    <?php echo utf8_encode($descricaoJogo) ?>
        </p>
    </div>

    <?php
} else {
    echo "Erro ao carregar jogo! Se o erro persistir, consulte o administrador do site.";
}
?>