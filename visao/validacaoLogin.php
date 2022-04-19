<?php

include_once '../controle/ControleUsuario.class.php';
include_once '../controle/ControleProfessor.class.php';
include_once '../controle/ControleAluno.class.php';

$controleUser = ControleUsuario::getInstancia();
$res = $controleUser->validarLogin($_POST ['user_login'], md5($_POST ['senha_login']));

if (count($res) > 0) {
    if ($res[0]['estaAprovado_usuario'] == false || $res[0]['estaAprovado_usuario'] == 0 && $res[0]['tipo_usuario'] == 3) {
        echo '<div id="alerta" class="alert col-sm-12 alert-danger" role="alert">
					<i class="glyphicon glyphicon-exclamation-sign"></i>
					<span style="font-size:15pt">Você ainda não tem permissão de acesso!</span>
				<br /> Apenas seu professor ou o administrador podem liberar seu acesso.</div>';
    } else if ($res[0]['estaAprovado_usuario'] == false || $res[0]['estaAprovado_usuario'] == 0 && $res[0]['tipo_usuario'] == 2) {
        echo '<div id="alerta" class="alert col-sm-12 alert-danger" role="alert">
					<i class="glyphicon glyphicon-exclamation-sign"></i>
					<span style="font-size:15pt">Você ainda não tem permissão de acesso!</span>
				<br /> Apenas o administrador pode liberar seu acesso.</div>';
    } else if ($res[0]['tipo_usuario'] == 2 && ($res[0]['estaAtivo_professor'] == false || $res[0]['estaAtivo_professor'] == null) || $res[0]['tipo_usuario'] == 3 && ($res[0]['estaAtivo_aluno'] == false || $res[0]['estaAtivo_aluno'] == null)) {
        echo '<div id="alerta" class="alert col-sm-12 alert-danger" role="alert">
					<i class="glyphicon glyphicon-exclamation-sign"></i>
					<span style="font-size:15pt">Sua conta está desativada!</span>
				<br /> Entre em contato com o administrador para ativar sua conta.</div>';
    } else {

        //Se o usuário aprovado for um professor pegar o 'id' dele
        $idProfessor = null;

        if ($res[0]['tipo_usuario'] == 2) {
            $controleProfessor = ControleProfessor::getInstancia();
            $res1 = $controleProfessor->getProfessorByIdUsuario($res[0]['id_usuario']);
            $idProfessor = $res1[0]['id_professor'];
        }

       

        session_start();

        $_SESSION['id_usuario'] = $res[0]['id_usuario'];
        $_SESSION['tipo_usuario'] = $res[0]['tipo_usuario'];
        $_SESSION['nome_usuario'] = $res[0]['nome_usuario'];
        $_SESSION['id_professor'] = $idProfessor;

        echo '<div id="alerta" class="alert col-sm-12 alert-success" role="alert">
					<i class="glyphicon glyphicon-ok"></i>
					<span style="font-size:15pt">Logado com sucesso!</span>
				<br /> Aguarde... Se você não ser redirecionado para o site, tente fazer o login novamente. 
			Se o erro persistir, consulte o administrador</div>';
    }
} else {

    echo '<div id="alerta" class="alert col-sm-12 alert-danger" role="alert">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				Login Inválido!</div>';
}

