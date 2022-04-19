<?php
include_once '../controle/ControleUsuario.class.php';

$controleUser = ControleUsuario::getInstancia ();
$acao = $_GET ['url'];

switch ($acao) {
	case 'cadastrar' :
		
		if ($controleUser->cadastrarUsuario($controleUser->novoUsuario ( 0, trim($_POST ['nome_usuario']), trim($_POST ['user_usuario']), trim($_POST ['email_usuario']), md5(trim($_POST['senha_usuario'])), $_POST ['tipo_usuario'], false, 1) )) {
			
			echo '<div id="alerta" class="alert col-sm-12 alert-success">
					<i class="glyphicon glyphicon-ok"></i>
					
					<span style="font-size:15pt">Cadastro realizado com sucesso!</span> 
				<br /> Aguarde até que seu professor ou o administrador libere seu acesso.</div>';
		} else {
			echo '<div id="alerta" class="alert col-sm-12 alert-danger">
				<i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
				<span style="font-size:15pt">Erro ao realizar o cadastro!</span> <br /> 
				Se o erro persistir, entre em contato com o administrador do sistema.</div>';
		}
		
		break;
	
	case 'validarUser' :
             if(empty($_POST['id_usuario'])) {
                $idUser = null;
            } else {
                $idUser = $_POST['id_usuario'];
            }
		$res = $controleUser->getUsuarioByUser($idUser, $_POST['user_usuario']);
		if (count($res) > 0) {
			echo 'Este usuário já existe!';
		}
		
		break;
	
	case 'validarEmail' :
            if(empty($_POST['id_usuario'])) {
                $idUser = null;
            }  else {
                $idUser = $_POST['id_usuario'];
            }
            $res = $controleUser->getUsuarioByEmail($idUser, $_POST['email_usuario']);
		if (count($res) > 0) {
			echo 'Este email já existe!';
		}
		
		break;
}
?>