<?php
$idAluno = $_GET['id'];
$nomeTurma = $_GET['nome'];
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 class="modal-title">Gerar relatório do aluno: <strong><?php echo $nomeTurma ?></strong></h3>
</div>
<div class="modal-body">

    <p>Escolha o período:</p>
    
    <div id="relatorioAluno-alert" class="alert col-sm-12 alert-danger"></div>
    <div id="statusAluno" class="alert col-sm-12 alert-info"></div>

    <form  id="formModal" class="form-inline">

        
        <input type="hidden" name="idAluno" id="idAluno" value="<?php echo $idAluno ?>" />
        <div class="form-group">
            <div id="errodataInicio" class="erros"></div>
            <label for="dataInicio">Data inicio:</label>
            <input id="dataInicioAluno" name="dataInicio" value="" class="datepicker form-control" type="text"/>
        </div>
        <div class="form-group">
            <div id="errodataFim" class="erros"></div>
            <label for="dataFim">Data fim:</label>
            <input id="dataFimAluno" name="dataFim" value="" class="datepicker form-control" type="text"/>
        </div>
    </form> 

</div>
<div class="modal-footer">
    <a id="geraRelatorioAluno" onclick="gerarRelatorio()" href="#" type="submit" class="btn btn-success">Gerar</a>
    <button type="submit" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
</div>
