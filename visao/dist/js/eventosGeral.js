var tipoUser=$("input[name=tipo_user]").val();var usuarioLogado=$("input[name=id_user]").val();var professorLogado=$("input[name=id_professor]").val();function cortarNomeUsuario(){var nomeUser=$("#nomeUser").html();nomeUser.substr(0,nomeUser.indexOf(' '));var ret=nomeUser.split(" ");var str1=ret[0];if(str1!==null||str1!==''){$("#nomeUser").html(str1)}}$(function(){$("#gerenciarPermissoes").css("display","none");cortarNomeUsuario();if(tipoUser==1||tipoUser==2){if(typeof atualizarTurmas=='function'){atualizarTurmas()}}if(tipoUser==1){atualizarNumeroSolicitacoesAcesso();var hora=new Date().getHours();if(hora>=0&&hora<=11){$("#cumprimentoAdm").html("Bom dia,")}if(hora>=12&&hora<=17){$("#cumprimentoAdm").html("Boa tarde,")}if(hora>=18&&hora<=23){$("#cumprimentoAdm").html("Boa noite,")}}$("#pesquisaTelasGrandes").bind("keydown",function(event){if(event.which=="13"){var pesquisa=$("#pesquisaTelasGrandes").val();pesquisar(pesquisa)}});$("#inputTelasMenores").bind("keydown",function(event){if(event.which=="13"){var pesquisa=$("#inputTelasMenores").val();pesquisar(pesquisa)}});$("#pesquisarUsuarios").bind("keydown",function(event){if(event.which=="13"){pesquisarUsuario($('#pesquisarUsuarios').val())}})});function atualizarNumeroSolicitacoesAcesso(){if(tipoUser==1){$.ajax({type:"GET",data:{url:"pegarNumeroSolicitacoesAcesso",pesquisa:null},url:"funcoesControle.php"}).done(function(result){if(result!=''){if(parseInt(result)>1||parseInt(result)===0){$("#solicit").html("solicitações")}else{$("#solicit").html("solicitação")}$("#nAcesso").html(result);$("#numeroSolicitacoesAcesso").html(result)}}).fail(function(){alert("Erro ao carregar solicitações de acesso.")})}}function abrirModalEditarPerfil(id,pagina,id_turma,nome_turma){$.ajax({type:"GET",data:{id:id,pagina:pagina,id_turma:id_turma,nome_turma:nome_turma},url:"modalEditarInformacoesPerfil.php"}).done(function(result){$("#modalEditarPerfil").html(result);usuarioCadastroEdicao=$("input[name=idUsuarioEdicao]").val()}).fail(function(){$("#modalAbrirTurma").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao carregar as turmas arquivadas.</div>");setTimeout(function(){$("#modalAbrirTurma").html('')},3000)})}