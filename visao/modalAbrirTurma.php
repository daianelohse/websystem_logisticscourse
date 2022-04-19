<?php
$id = $_GET['id'];
$nome = $_GET['nome'];
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class="modal-title">Abrir turma</h3>
</div>
<div class="modal-body">

    <div id="abrirTurma-alert" style="display:none"></div>

    <p>Tem certeza que deseja abrir a turma <strong><?php echo $nome ?></strong> novamente?</p>

</div>
<div class="modal-footer">
    <div class="botoesModalConfirmacao" style="display: inline">
        <button onclick="confirmarAbrirTurmaArquivada(<?php echo $id ?>)" type="button" class="btn btn-success">Sim</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
    </div>
</div>
