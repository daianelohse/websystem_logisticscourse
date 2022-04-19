<?php
$idTurma = $_GET['id'];
$nomeTurma = $_GET['nome'];
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class="modal-title">Excluir turma permanentemente</h3>
</div>
<div class="modal-body">
    <div id="excluirTurma-alert"></div>
    <p>Tem certeza que deseja excluir permanentemente a turma <strong><?php echo $nomeTurma ?></strong>?</p>
</div>
<div class="modal-footer">
    <div class="botoesModalConfirmacao" style="display: inline">
        <button type="submit" class="btn btn-success" id="confirmarArquivarTurma" onclick="confirmarExcluirTurma('<?php echo $idTurma ?>')">Sim</button>
        <button type="submit" class="btn btn-danger" data-dismiss="modal">Não</button>
    </div>
</div>
