<?php
include_once '../controle/ControleUsuario.class.php';
$idUser = $_GET['id'];
$pagina = $_GET['pagina'];

if ($pagina == 'controle') {
    $idTurma = $_GET['id_turma'];
    $nomeTurma = $_GET['nome_turma'];
    ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="abrirAlunosTurma(<?php echo $idTurma ?>, <?php echo "'$nomeTurma'" ?>)">&times;</button>
        <h3 class="modal-title">Editar Informações de Usuário</h3> 
    </div>

<?php } else { ?>
    <div class="modal-header">
        <button class="close" data-dismiss="modal" aria-hidden="true" onclick="location.reload();">&times;</button>
        <h3 class="modal-title">Editar Informações de Usuário</h3>
    </div>
<?php } ?>
<div id="editarInformacoesPerfil" class="modal-body">
    <div id="signup-alert"></div>
    <div id="removerAluno-alert" style="display:none"></div>
    <form id="signupform" class="form-horizontal" role="form" action="">
        <input type="hidden" value="<?php echo $idUser ?>" id="idUsuarioEdicao" name="idUsuarioEdicao" />

        <?php
        $controleUsuario = ControleUsuario::getInstancia();
        $res = $controleUsuario->getUsuarioById($idUser);
        ?>

        <div id="erronome" class="erros"></div>
        <div class="form-group">
            <label for="firstname" class="col-md-3 control-label">Nome</label>
            <div class="col-md-9">
                <input type="text" id="nome" class="form-control" name="firstname" placeholder="Nome Completo" value="<?php echo $res[0]['nome_usuario']; ?>">
            </div>
        </div>


        <div id="errousuario" class="erros"></div>
        <div class="form-group">
            <label for="lastname" class="col-md-3 control-label">Usuário</label>
            <div class="col-md-9">
                <input type="text" id="usuario" class="form-control" name="lastname" placeholder="Usuário" value="<?php echo $res[0]['user_usuario']; ?>">
            </div>
        </div>


        <div id="erroemail" class="erros"></div>
        <div class="form-group">
            <label for="email" class="col-md-3 control-label">Email</label>
            <div class="col-md-9">
                <input type="text" id="email" class="form-control" name="email" placeholder="Endereço de email" value="<?php echo $res[0]['email_usuario']; ?>">
            </div>
        </div>
        <hr/>
        
        <span id="observacaoSenhaEditar">* Se você não quiser modificar a sua senha, apenas deixe os campos abaixo em branco. </span>

        <div id="errosenha" class="erros"></div>
        <div class="form-group">
           
            <label for="password" class="col-md-3 control-label">Nova senha</label>
            <div class="col-md-9">
                <input type="password" id="senha" class="form-control" name="passwd" placeholder="Nova senha" value="">
            </div>
        </div>


        <div id="errorepetirSenha" class="erros"></div>
        <div class="form-group">
            <label for="password" class="col-md-3 control-label">Repetir nova senha</label>
            <div class="col-md-9">
                <input type="password" id="repetirSenha" class="form-control" name="passwd" placeholder="Repetir nova senha" value="">
            </div>
        </div>
    </form>
</div>

<div class="modal-footer">
    <div id="editarInformacoesPerfilBotoes" class="botoesModalConfirmacao" style="display: inline">

        <?php
        if ($pagina == 'controle') {
            ?>

            <button id="btn-signup" type="button" class="btn btn-success" onclick="cadastrar('editar',<?php echo $idUser ?>, 'adm')">Editar</button>

        <?php } else { ?>

            <button id="btn-signup" type="button" class="btn btn-success" onclick="cadastrar('editar',<?php echo $idUser ?>, 'user')">Editar</button>

        <?php } ?>

        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
    </div>
</div>