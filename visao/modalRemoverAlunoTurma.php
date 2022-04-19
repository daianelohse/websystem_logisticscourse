<?php
$idTurmaRemover = $_GET['id_turma'];
$idAlunoRemover = $_GET['id_aluno'];
$nome = $_GET['nome'];
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class="modal-title">Remover aluno da turma</h3>
</div>
<div class="modal-body">

    <div id="removerAluno-alert" style="display:none"></div>

    <p>Tem certeza que deseja remover o aluno <strong><?php echo $nome ?></strong> da turma?</p>

</div>
<div class="modal-footer">
    <div class="botoesModalConfirmacao" style="display: inline">
        <button onclick="confirmarRemoverAluno(<?php echo $idTurmaRemover ."," .$idAlunoRemover ?>)" type="button" class="btn btn-success">Sim</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
    </div>
</div>
