function todosUsuarios(pesquisa) {
    $("#listaTodosUsuarios").show();
    $("#listaUsuariosSemPermissao").hide();
    $("#listaUsuariosInativos").hide();

    $("#pendentesUsuarios").removeClass("menuItemClicado");
    $("#inativosUsuarios").removeClass("menuItemClicado");
    $("#todosUsuarios").addClass("menuItemClicado");

    $.ajax({
        type: "GET",
        data: {url: "pegarTodosUsuariosComPermissao", pesquisa: pesquisa},
        url: "funcoesControle.php"
    }).done(function (result) {
        $("#listaTodosUsuarios").html(result);
        if($("#listaTodosUsuarios p:first-child").hasClass("mensagemUsuarioPermissao")) {
           $("#grupoPesquisaUsuarios").hide();
        } else {
           $("#grupoPesquisaUsuarios").show(); 
        }
    }).fail(function () {
        $("#listaUsuariosSemPermissao").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar os usuários sem permissão, tente novamente. Se o erro persistir, consulte o administrador.</div>");

    });
}

function abrirGerenciamentoAcesso(pesquisa) {

    $("#gerenciarPermissoes").show();
    $("#listaTodosUsuarios").hide();
    $("#listaUsuariosSemPermissao").show();
    $("#listaUsuariosInativos").hide();

    $("#todosUsuarios").removeClass("menuItemClicado");
    $("#inativosUsuarios").removeClass("menuItemClicado");
    $("#pendentesUsuarios").addClass("menuItemClicado");

    //ajax
    $.ajax({
        type: "GET",
        data: {url: "pegarUsuariosSemPermissao", pesquisa: pesquisa},
        url: "funcoesControle.php"
    }).done(function (result) {
        $("#listaUsuariosSemPermissao").html(result);
        if($("#listaUsuariosSemPermissao p:first-child").hasClass("mensagemUsuarioPermissao")) {
           $("#grupoPesquisaUsuarios").hide();
        } else {
           $("#grupoPesquisaUsuarios").show(); 
        }
    }).fail(function () {
        $("#listaUsuariosSemPermissao").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar os usuários sem permissão, tente novamente. Se o erro persistir, consulte o administrador.</div>");

    });
}

function usuariosInativos(pesquisa) {

    $("#listaTodosUsuarios").hide();
    $("#listaUsuariosSemPermissao").hide();
    $("#listaUsuariosInativos").show();

    $("#todosUsuarios").removeClass("menuItemClicado");
    $("#pendentesUsuarios").removeClass("menuItemClicado");
    $("#inativosUsuarios").addClass("menuItemClicado");

    //ajax
    $.ajax({
        type: "GET",
        data: {url: "pegarUsuariosInativos", pesquisa: pesquisa},
        url: "funcoesControle.php"
    }).done(function (result) {
        $("#listaUsuariosInativos").html(result);
        if($("#listaUsuariosInativos p:first-child").hasClass("mensagemUsuarioPermissao")) {
           $("#grupoPesquisaUsuarios").hide();
        } else {
           $("#grupoPesquisaUsuarios").show(); 
        }
    }).fail(function () {
        $("#listaUsuariosInativos").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar os usuários sem permissão, tente novamente. Se o erro persistir, consulte o administrador.</div>");

    });
}

var ultimoUsuarioAberto = null;
var ultimaAprovacaoAberta = null;
var idPermissao = -1;

function fecharPermissaoUsuario() {
    var perProf = document.getElementById("permissaoProfessor");
    var perAlun = document.getElementById("permissaoAluno");
    var perExc = document.getElementById("permissaoExcluir");

    if (perProf !== null || perProf === 'undefined') {
        perProf.checked = false;
    }

    if (perAlun !== null || perAlun === 'undefined') {
        perAlun.checked = false;
    }

    if (perExc !== null || perExc === 'undefined') {
        perExc.checked = false;
    }

    if (ultimaAprovacaoAberta !== null) {
        cancelarAprovacao(ultimaAprovacaoAberta);
    }
    if (ultimoUsuarioAberto !== null) {
        $(".liberacaoRapida" + ultimoUsuarioAberto).show();
        $("#user" + ultimoUsuarioAberto + " .iconeClose").removeClass("glyphicon-remove");
        $("#permissaoUser" + ultimoUsuarioAberto).hide();
        $(".botao" + ultimoUsuarioAberto).hide();
        $("#user" + ultimoUsuarioAberto).css("background-color", "#fff");
        $("#user" + ultimoUsuarioAberto + " .nomeUser").css("font-weight", "normal");
    }
}

function abrirPermissaoUsuario(id_usuario) {
    fecharPermissaoUsuario();
    if (ultimaAprovacaoAberta !== null) {
        cancelarAprovacao(ultimaAprovacaoAberta);
    }
    $(".liberacaoRapida" + id_usuario).hide();
    $(".botoesGerenciarPermissao").hide();
    $(".botao" + id_usuario).show();
    $("#user" + id_usuario + " .iconeClose").addClass("glyphicon glyphicon-remove");
    $("#permissaoUser" + id_usuario).show();
    $("#user" + id_usuario).css("background-color", "#ccc");
    $("#user" + id_usuario + " .nomeUser").css("font-weight", "bolder");
    ultimoUsuarioAberto = id_usuario;
}

function cancelarAprovacao(id_user) {
    $(".liberacaoRapida" + id_user).show();
    $(".userDetalhe" + id_user).show();
    $(".confirmarPermissaoUser" + id_user).hide();
    $("#user" + id_user).css("background-color", "#fff");
    $("#user" + id_user + " .nomeUser").css("font-weight", "normal");
}

function confirmarAprovacao(id_user, tipo_user) {
    var user = tipo_user;
    if (idPermissao !== -1 && idPermissao !== -4) {
        tipo_user = idPermissao;
    }
    
      $("#feedbackCadastro" + id_user).show().html('<span style="color:#000000">Salvando...</span>');    
   

    //Se for igual a 4, o usuário é deletado
    if (idPermissao == 4) {
        //Se a lista de "pendentes" não estiver aberta, o usuário é apenas desativado
        if (!$("#pendentesUsuarios").hasClass("menuItemClicado")) {
            $.ajax({
                type: "POST",
                data: {id_usuario: id_user, permissao_antiga: user},
                url: "funcoesControle.php?url=desativarUsuario"
            }).done(function (result) {
                if (result === 'sucesso') {
                    $(".liberacaoRapida" + id_user).hide();
                    $("#feedbackCadastro" + id_user).show();
                    $("#feedbackCadastro" + id_user).html("CONFIRMADO!");
                    idPermissao = -1;
                    setTimeout(function () {
                        if ($("#todosUsuarios").hasClass("menuItemClicado")) {
                            todosUsuarios(null);
                        } else {
                            abrirGerenciamentoAcesso(null);
                        }

                        atualizarNumeroSolicitacoesAcesso();
                    }, 2000);

                } else {
                    $("#feedbackCadastro" + id_user).html("ERRO!").css('color', 'red');
                    setTimeout(function () {
                        $("#feedbackCadastro" + id_user).html("").css('display', 'none');
                        if ($("#todosUsuarios").hasClass("menuItemClicado")) {
                            todosUsuarios(null);
                        } else {
                            abrirGerenciamentoAcesso(null);
                        }
                    }, 2000);
                }
            });
        } else {
            $.ajax({
                type: "POST",
                data: {id_usuario: id_user, permissao_antiga: user},
                url: "funcoesControle.php?url=excluirUsuario"
            }).done(function (result) {
                if (result === 'sucesso') {
                    $(".liberacaoRapida" + id_user).hide();
                    $("#feedbackCadastro" + id_user).show();
                    $("#feedbackCadastro" + id_user).html("CONFIRMADO!");
                    idPermissao = -1;
                    setTimeout(function () {
                        if ($("#todosUsuarios").hasClass("menuItemClicado")) {
                            todosUsuarios(null);
                        } else {
                            abrirGerenciamentoAcesso(null);
                        }

                        atualizarNumeroSolicitacoesAcesso();
                    }, 2000);

                } else {
                    $("#feedbackCadastro" + id_user).html("ERRO!").css('color', 'red');
                    setTimeout(function () {
                        $("#feedbackCadastro" + id_user).html("").css('display', 'none');
                        if ($("#todosUsuarios").hasClass("menuItemClicado")) {
                            todosUsuarios(null);
                        } else {
                            abrirGerenciamentoAcesso(null);
                        }
                    }, 2000);
                }
            });
        }
    } else {
        if (!$("#todosUsuarios").hasClass("menuItemClicado")) {
            user = -1;
        }
        $.ajax({
            type: "POST",
            data: {id_usuario: id_user, tipo_usuario: tipo_user, permissao_antiga: user},
            url: "funcoesControle.php?url=cadastrarPermissaoUsuario"
        }).done(function (result) {
            if (result === 'sucesso') {
                $(".liberacaoRapida" + id_user).hide();
                $("#feedbackCadastro" + id_user).show();
                $("#feedbackCadastro" + id_user).html("CONFIRMADO!");
                idPermissao = -1;
                setTimeout(function () {
                    if ($("#todosUsuarios").hasClass("menuItemClicado")) {
                        todosUsuarios(null);
                    } else {
                        abrirGerenciamentoAcesso(null);
                    }
                    atualizarNumeroSolicitacoesAcesso();
                }, 2000);

            } else {
                $("#feedbackCadastro" + id_user).html("ERRO!").css('color', 'red');
                setTimeout(function () {
                    $("#feedbackCadastro" + id_user).html("").css('display', 'none');
                    if ($("#todosUsuarios").hasClass("menuItemClicado")) {
                        todosUsuarios(null);
                    } else {
                        abrirGerenciamentoAcesso(null);
                    }
                }, 2000);
            }
        });
    }
}

function mostrarBotoes() {
    $(".botoesGerenciarPermissao").show();
}

function aprovarPermissao(id_user) {
    if (ultimaAprovacaoAberta !== null) {
        cancelarAprovacao(ultimaAprovacaoAberta);
    }
    ultimaAprovacaoAberta = id_user;
    fecharPermissaoUsuario();
    $(".liberacaoRapida" + id_user).hide();
    $(".botoesGerenciarPermissao").hide();
    $(".userDetalhe" + id_user).hide();
    $(".confirmarPermissaoUser" + id_user).show();
    $("#user" + id_user).css("background-color", "#ccc");
    $("#user" + id_user + " .nomeUser").css("font-weight", "bolder");
}

function aprovarPermissaoSegundaOpcao(idUser, nome) {
    if (ultimaAprovacaoAberta !== null) {
        cancelarAprovacao(ultimaAprovacaoAberta);
    }

    ultimaAprovacaoAberta = idUser;

    if ($("#permissaoUser" + idUser + " #permissaoExcluir").is(":checked")) {
        idPermissao = 4;
        $("#confirmarSegundaOpcao" + idUser).html("Tem certeza que você deseja <strong>EXCLUIR</strong> o usuário " + nome + "?");
    }
    if ($("#permissaoUser" + idUser + " #permissaoProfessor").is(":checked")) {
        idPermissao = 2;
        $("#confirmarSegundaOpcao" + idUser).html("Tem certeza que deseja liberar o acesso do tipo <strong>PROFESSOR</strong> para " + nome + "?");

    }
    if ($("#permissaoUser" + idUser + " #permissaoAluno").is(":checked")) {
        idPermissao = 3;
        $("#confirmarSegundaOpcao" + idUser).html("Tem certeza que deseja liberar o acesso do tipo <strong>ALUNO</strong> para " + nome + "?");
    }

    fecharPermissaoUsuario();




    $(".liberacaoRapida" + idUser).hide();
    $(".botoesGerenciarPermissao").hide();
    $(".userDetalhe" + idUser).hide();
    $(".confirmarPermissaoUser" + idUser).show();
    $(".confirmarPrimeiraOpcao" + idUser).hide();
    $("#confirmarSegundaOpcao" + idUser).show();
    $("#user" + idUser).css("background-color", "#ccc");
    $("#user" + idUser + " .nomeUser").css("font-weight", "bolder");
}

function confirmarAtivarUsuario(id_user, tipo_user) {
    $("#feedbackCadastro" + id_user).show().html('<span style="color:#000000">Salvando...</span>');
    $.ajax({
        type: "POST",
        data: {id_usuario: id_user, tipo_usuario: tipo_user},
        url: "funcoesControle.php?url=ativarUsuario"
    }).done(function (result) {
        if (result === 'sucesso') {
            $(".liberacaoRapida" + id_user).hide();
            $("#feedbackCadastro" + id_user).show();
            $("#feedbackCadastro" + id_user).html("CONFIRMADO!");
            setTimeout(function () {
                usuariosInativos(null);
            }, 2000);

        } else if (result == 'outro') {
            $("#feedbackCadastro" + id_user).html("<br />ERRO! Este usuário já possui outra permissão. Ele não pode ser 'aluno' e 'professor' simultâneamente.").css('color', 'red');
            setTimeout(function () {
                $("#feedbackCadastro" + id_user).html("").css('display', 'none');
                usuariosInativos(null);
            }, 7000);
        } else {
            $("#feedbackCadastro" + id_user).html("ERRO!").css('color', 'red');
            setTimeout(function () {
                $("#feedbackCadastro" + id_user).html("").css('display', 'none');
                usuariosInativos(null);
            }, 2000);
        }
    });
}

function pesquisarUsuario(pesquisa) {
   $('#pesquisarUsuarios').val('');
    if ($("#pendentesUsuarios").hasClass("menuItemClicado")) {
        abrirGerenciamentoAcesso(pesquisa);
    } else if ($("#todosUsuarios").hasClass("menuItemClicado")) {
        todosUsuarios(pesquisa);
    } else {
        usuariosInativos(pesquisa);
    }
}

function limparPesquisaUsuario() {
    $('#pesquisarUsuarios').val('');
    if ($("#pendentesUsuarios").hasClass("menuItemClicado")) {
        abrirGerenciamentoAcesso(null);
    } else if ($("#todosUsuarios").hasClass("menuItemClicado")) {
        todosUsuarios(null);
    } else {
        usuariosInativos(null);
    }

}