/**
 * EVENTOS TURMA
 */

var erroCadastroTurma = false;
var nomeTurma = null;
var turmaCadastro = null;
var professoresSelecionados;
var idTurmaEdicao;
var edicao = false;


function atualizarTurmas() {
    if ($("#breadPlanoDeAula").length > 0) {
        $("#planoDeAula").hide();
    }
    $("#turmas").show();
    $("#alunos").hide();
    $(".breadcrumb").html('<li><a href="#" class="active" onclick="atualizarTurmas()">Turmas</a></li>');
    //Pegar Turmas
    if (tipoUser == 1) {
        //Pegar todas, se o usuário logado for o administrador
        $.ajax({
            type: "GET",
            url: "funcoesControle.php?url=pegarTurmas"
        }).done(function (result) {
            $("#listaTurmas").html(result);
        }).fail(function () {
            $("#listaTurmas").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar as turmas, tente novamente. Se o erro persistir, consulte o administrador.</div>");
        });
    } else {
        //Se o usuário logado for o professor, pegar apenas as turmas do mesmo
        $.ajax({
            type: "GET",
            url: "funcoesControle.php?url=pegarTurmasDoProfessor&id=" + professorLogado
        }).done(function (result) {
            if (result !== '') {
                $("#listaTurmas").html(result);
            }
        });
    }
}

function cadastrarNovaTurma() {

    if (validar()) {
        $("#addTurma-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Salvando...');    
   
        limpaMensagem("selectProfessores");
        limpaMensagem("turma");
        limpaMensagem("selectProfessores");
        turmaCadastro = $("#turma").val();
        if (!isNaN(turmaCadastro)) {
            turmaCadastro.toUpperCase();
        }

        if (tipoUser == 1) {
            professor = professoresSelecionados;
        } else {
            professor = professorLogado;
        }

        eNovaTurma = true;

        if ($("#turma").val() !== '') {
            $.ajax({
                type: "POST",
                data: {turma: turmaCadastro.toUpperCase(), professor: professor},
                url: "funcoesControle.php?url=cadastrarTurma"
            }).done(function (result) {
                $("#addTurma-alert").html(result);
                setTimeout(function () {
                    $("#addTurma-alert").html('');
                }, 3000);
                if ($('#alerta').hasClass('alert-success')) {
                    $("#turma").val('');
                    atualizarTurmas();
                    abrirCadastroTurmas(tipoUser);
                }
            }).fail(function () {
                $("#addTurma-alert").html('');
                $("#addTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao cadastrar</div>");
            });
        } else if (tipoUser == 2 && $('#selectTurmas').val() != '') {
            idTurma = $('#selectTurmas').val();
            nomeTurma = $('#selectTurmas option:selected').html();
            $.ajax({
                type: "POST",
                data: {idTurma: idTurma, nomeTurma: nomeTurma, idProfessor: professor},
                url: "funcoesControle.php?url=cadastrarTurmaProfessor"
            }).done(function (result) {
                $("#addTurma-alert").html(result);
                setTimeout(function () {
                    $("#addTurma-alert").html('');
                }, 3000);
                if ($('#alerta').hasClass('alert-success')) {
                    $("#turma").val('');
                    atualizarTurmas();
                    abrirCadastroTurmas(tipoUser);
                }
            }).fail(function () {
                $("#addTurma-alert").html('');
                $("#addTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao cadastrar</div>");
            });
        }
    }
}

function validar() {
    var entrouValidacao = 0;
    var respondeuValidacao = 0;
    var erro = false;
    $("#addTurma-alert").html("");
    limpaMensagem("turma");
    limpaMensagem("selectProfessores");
    limpaMensagem("selectTurmas");
    var nomeTurma = $("#turma").val();

    if (nomeTurma != '' && !edicao) {
//Pegar todas, se o usuário logado for o administrador
        $.ajax({
            type: "GET",
            async: false,
            data: {nomeTurma: nomeTurma},
            url: "funcoesControle.php?url=validarNomeTurma",
            beforeSend: function (xhr) {
                entrouValidacao++;
            }
        }).done(function (result) {
            if (result != '') {
                mensagem("turma", result);
                erro = true;
            }
            respondeuValidacao++;
        }).fail(function () {
            $("#addTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar as turmas, tente novamente. Se o erro persistir, consulte o administrador.</div>");
        });
    } else {
        entrouValidacao++;
        respondeuValidacao++;
    }



    if ($("#turma").val() == '' && $('#selectTurmas').val() == '') {
        erro = true;
    }

    if (tipoUser == 2) {
        if ($("#turma").val() !== '' && ($('#selectTurmas').val() !== '' && $('#selectTurmas option').size() > 0)) {
            $("#addTurma-alert").html("<div class='alert alert-danger'><i class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></i>Os dois campos não devem estar preenchidos simultâneamente</div>");
            erro = true;
        }
    }



    var professor;
    if (tipoUser !== 1) {
        professor = professorLogado;
    }

    if (tipoUser == 1) {
        if (professoresSelecionados.length > 0) {
            limpaMensagem("selectProfessores");
        } else {
            mensagem("selectProfessores", "Selecione um professor!");
            erro = true;
            return false;
        }
    }



    if (entrouValidacao === 1 && respondeuValidacao === 1) {
        if (!erro) {
            return true;
        } else {
            return false;
        }
    }

}

function abrirCadastroTurmas(tipo_user) {
    if (tipoUser == 1) {
        pegarProfessoresCadastro();
    }
    $('#adicionarTurma').show();
    $('#salvarTurma').show();
    $('#editarTurma').hide();
    $("#turma").val('');
    $("#labelTurma").html('Nova Turma');

    if (tipoUser == 1) {
        professor = professoresSelecionados;
    } else {
        professor = professorLogado;
    }
    $.ajax({
        type: "GET",
        url: "funcoesControle.php?url=pegarTurmasSelect"
    }).done(function (result) {
        $("#selectTurmas").html(result);
        if (!result == '') {
            $("#selectTurmas").show();
            $("#labelTurmasSemEsteProfessor").show();
        } else {
            $("#selectTurmas").hide();
            $("#labelTurmasSemEsteProfessor").hide();
        }

    }).fail(function () {
        alert("Ocorreu ao carregar as turmas");
    });
}

$(function () {
    atualizarNumeroTurmasArquivadas();
});

function pegarProfessoresCadastro() {
    $(".token-input-list").remove();
    $("#cadastrarTurma").tokenInput("funcoesControle.php?url=pegarprofessoresPesquisa", {
        propertyToSearch: "nome_professor",
        searchingText: "Pesquisando...",
        hintText: "Pesquise o nome de um professor...",
        noResultsText: "Nenhum professor encontrado",
        resultsFormatter: function (item) {
            return "<li>" + "<div id='pesquisaProfessores' class='glyphicon glyphicon-user' title='" + item.nome_professor + "'></div>" +
                    "<div style='display: inline-block; padding-left: 10px;'><div class='full_name'>" + item.nome_professor + "</div><div class='email'>" + item.email_usuario + "</div></div></li>";
        },
        tokenFormatter: function (item) {
            return "<span id='professorSelecionado'>" + item.nome_professor + "</span>";
        },
        onAdd: function (item) {

            if (professoresSelecionados.indexOf(item.id_professor) > -1) {
                return false;
            } else {
                professoresSelecionados.push(item.id_professor);
            }

        },
        onDelete: function (item) {

            var index = professoresSelecionados.indexOf(item.id_professor);
            if (index > -1) {
                professoresSelecionados.splice(index, 1);
            }
        },
        onReady: function () {
            professoresSelecionados = null;
            professoresSelecionados = new Array();
        }
    });
}

function pegarProfessoresEdicao(turmaEdicao) {
    var estaPopulando = true;
    professoresSelecionados = new Array();
    if (turmaEdicao != null) {
        var pre = null;
        $.ajax({
            async: false,
            type: "GET",
            url: "funcoesControle.php?url=pegarProfessoresByIdTurma&id=" + turmaEdicao
        }).done(function (result) {
            pre = "[" + result + "]";
            pre = JSON.parse(pre);

        });
    } else {
        pre = null;
    }
    $("#cadastrarTurma").tokenInput("funcoesControle.php?url=pegarprofessoresPesquisa", {
        propertyToSearch: "nome_professor",
        searchingText: "Pesquisando...",
        hintText: "Pesquise o nome de um professor...",
        noResultsText: "Nenhum professor encontrado",
        prePopulate: pre,
        resultsFormatter: function (item) {
            return "<li>" + "<div id='pesquisaProfessores' class='glyphicon glyphicon-user' title='" + item.nome_professor + "'></div>" +
                    "<div style='display: inline-block; padding-left: 10px;'><div class='full_name'>" + item.nome_professor + "</div><div class='email'>" + item.email_usuario + "</div></div></li>";
        },
        tokenFormatter: function (item) {
            if (professoresSelecionados.indexOf(item.id_professor) > -1) {
                return false;
            } else {
                professoresSelecionados.push(item.id_professor);
            }

            return "<span id='professorSelecionado'>" + item.nome_professor + "</span>";
        },
        onAdd: function (item) {
            if (professoresSelecionados.indexOf(item.id_professor) > -1) {
                return false;
            } else {
                professoresSelecionados.push(item.id_professor);
            }
        },
        onDelete: function (item) {
            var index = professoresSelecionados.indexOf(item.id_professor);
            if (index > -1) {
                professoresSelecionados.splice(index, 1);
            }
        },
    });
}

function editarTurma(id, nome) {
    edicao = true;
    idTurmaEdicao = id;
    $("#inputProfessor ul:first-child").remove();
    pegarProfessoresEdicao(id);
    $('#adicionarTurma').show();
    $('#salvarTurma').hide();
    $('#editarTurma').show();
    $("#turma").val(nome);
    $("#labelSelectTurma").hide();
    $("#labelTurma").html("Editar Nome");
    $("#selectTurmas").hide();
}

function salvarEdicaoTurma() {
    if (validar()) {
        $("#addTurma-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Salvando alterações...');    
        limpaMensagem("selectProfessores");
        limpaMensagem("turma");
        limpaMensagem("selectProfessores");
        turmaCadastro = $("#turma").val();
        if (tipoUser == 1) {
            professor = professoresSelecionados;
        } else {
            professor = professorLogado;
        }

        $.ajax({
            type: "POST",
            data: {idTurma: idTurmaEdicao, nomeTurma: turmaCadastro.toUpperCase(), idProfessor: professor},
            url: "funcoesControle.php?url=editarTurma"
        }).done(function (result) {
            $("#addTurma-alert").html(result);
            setTimeout(function () {
                $("#addTurma-alert").html('');
            }, 3000);
            if ($('#alerta').hasClass('alert-success')) {
                $("#turma").val('');
                atualizarTurmas();
                abrirCadastroTurmas(tipoUser);
            }
        }).fail(function () {
            $("#addTurma-alert").html('');
            $("#addTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao cadastrar</div>");
        });
    }
}

function arquivarTurma(id, nome) {
    $.ajax({
        type: "GET",
        data: {id: id, nome: nome},
        url: "modalArquivarTurma.php"
    }).done(function (result) {
        $("#modalArquivarTurma").html(result);
    }).fail(function () {
        $("#modalArquivarTurma").html("<div class='alert alert-danger'>Desculpe, ocorreu um erro.</div>");
    });
}

function confirmarArquivarTurma(id) {
    $("#arquivarTurma-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Arquivando...');    
   
    $.ajax({
        type: "POST",
        data: {id: id},
        url: "funcoesControle.php?url=arquivarTurma"
    }).done(function (result) {
        $("#arquivarTurma-alert").html(result);
        if ($('#alerta').hasClass('alert-success')) {
            $(".botoesModalConfirmacao").hide();
        }
        setTimeout(function () {
            if ($('#alerta').hasClass('alert-success')) {
                atualizarTurmas();
                atualizarNumeroTurmasArquivadas();
                atualizarTurmasArquivadas();
                $('.modalArquivarTurma').modal('hide');
            }
            $("#arquivarTurma-alert").html('');
        }, 2000);
    }).fail(function () {
        $("#arquivarTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao arquivar turma.</div>");
        setTimeout(function () {
            $("#arquivarTurma-alert").html('');
        }, 3000);
    });
}

function atualizarNumeroTurmasArquivadas() {
    if (tipoUser == 1) {
        $.ajax({
            type: "GET",
            url: "funcoesControle.php?url=numeroTurmasArquivadas"
        }).done(function (result) {
            $("#nTurmasArquivadas").html(result);
        }).fail(function () {
            $("#nTurmasArquivadas").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao atualizar o número de turmas arquivadas.</div>");
            setTimeout(function () {
                $("#nTurmasArquivadas").html('');
            }, 3000);
        });
    } else {
        $.ajax({
            type: "GET",
            data: {id: professorLogado},
            url: "funcoesControle.php?url=numeroTurmasArquivadasByIdProfessor"
        }).done(function (result) {

            $("#nTurmasArquivadas").html(result);
        }).fail(function () {
            $("#nTurmasArquivadas").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao atualizar o número de turmas arquivadas.</div>");
            setTimeout(function () {
                $("#nTurmasArquivadas").html('');
            }, 3000);
        });
    }
}

function abrirTurmasArquivadas() {
    $("#nTurmasArquivadas").hide();
    $("#iconCollapseTurmasArquivadas").removeClass("glyphicon-collapse-down");
    $("#iconCollapseTurmasArquivadas").addClass("glyphicon-collapse-up");
    $("#lstTurmasArquivadas").show();
    atualizarTurmasArquivadas();
}

function atualizarTurmasArquivadas() {

    if (tipoUser == 1) {
        $.ajax({
            type: "GET",
            url: "funcoesControle.php?url=pegarTurmasArquivadas"
        }).done(function (result) {
            $("#lstTurmasArquivadas").html(result);
        }).fail(function () {
            $("#lstTurmasArquivadas").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao carregar as turmas arquivadas.</div>");
            setTimeout(function () {
                $("#lstTurmasArquivadas").html('');
            }, 3000);
        });
    }
    if (tipoUser == 2) {
        $.ajax({
            type: "GET",
            data: {id: professorLogado},
            url: "funcoesControle.php?url=pegarTurmasArquivadasByIdProfessor"
        }).done(function (result) {
            $("#lstTurmasArquivadas").html(result);
        }).fail(function () {
            $("#lstTurmasArquivadas").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao carregar as turmas arquivadas.</div>");
            setTimeout(function () {
                $("#lstTurmasArquivadas").html('');
            }, 3000);
        });
    }
}

function fecharTurmasArquivadas() {
    $("#nTurmasArquivadas").show();
    $("#lstTurmasArquivadas").hide();
    $("#iconCollapseTurmasArquivadas").removeClass("glyphicon-collapse-up");
    $("#iconCollapseTurmasArquivadas").addClass("glyphicon-collapse-down");
}

function collapseTurmas() {
    if ($("#iconCollapseTurmasArquivadas").hasClass("glyphicon-collapse-up")) {
        fecharTurmasArquivadas();
    } else if ($("#iconCollapseTurmasArquivadas").hasClass("glyphicon-collapse-down")) {
        abrirTurmasArquivadas();
    }
}

function abrirTurmaFechada(id, nome) {
    $.ajax({
        type: "GET",
        data: {id: id, nome: nome},
        url: "modalAbrirTurma.php"
    }).done(function (result) {
        $("#modalAbrirTurma").html(result);
    }).fail(function () {
        $("#modalAbrirTurma").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao carregar as turmas arquivadas.</div>");
        setTimeout(function () {
            $("#modalAbrirTurma").html('');
        }, 3000);
    });
}

function confirmarAbrirTurmaArquivada(id) {
      $("#abrirTurma-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Salvando alterações...');    
   
    $.ajax({
        type: "POST",
        data: {id: id},
        url: "funcoesControle.php?url=abrirTurmaArquivada"
    }).done(function (result) {
        $('#abrirTurma-alert').show();
        $("#abrirTurma-alert").html(result);
        if ($('#alerta').hasClass('alert-success')) {
            $(".botoesModalConfirmacao").hide();
        }
        setTimeout(function () {
            if ($('#alerta').hasClass('alert-success')) {
                atualizarTurmas();
                abrirTurmasArquivadas()
                atualizarNumeroTurmasArquivadas();
                $('#abrirTurma-alert').hide();
                $('.modalAbrirTurma').modal('hide');
            }
            $("#abrirTurma-alert").html('');
        }, 2000);
    }).fail(function () {
        $("#abrirTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao abrir turma.</div>");
        setTimeout(function () {
            $("#abrirTurma-alert").html('');
        }, 3000);
    });
}

function excluirTurma(id, nome) {
    $.ajax({
        type: "GET",
        data: {id: id, nome: nome},
        url: "modalExcluirTurma.php"
    }).done(function (result) {
        $("#modalExcluirTurma").html(result);
    }).fail(function () {
        $("#modalExcluirTurma").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao excluir turma.</div>");
        setTimeout(function () {
            $("#modalExcluirTurma").html('');
        }, 3000);
    });
}

function confirmarExcluirTurma(id) {
      $("#excluirTurma-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Removendo...');    
   
    $.ajax({
        type: "POST",
        data: {id: id},
        url: "funcoesControle.php?url=excluirTurma"
    }).done(function (result) {
        $('#excluirTurma-alert').show();
        $("#excluirTurma-alert").html(result);
        if ($('#alerta').hasClass('alert-success')) {
            $(".botoesModalConfirmacao").hide();
        }
        setTimeout(function () {
            if ($('#alerta').hasClass('alert-success')) {
                atualizarTurmasArquivadas();
                atualizarNumeroTurmasArquivadas();
                $('#excluirTurma-alert').hide();
                $('.modalExcluirTurma').modal('hide');
            }
            $("#excluirTurma-alert").html('');
        }, 2000);
    }).fail(function () {
        $("#excluirTurma-alert").html("<div id='alert' class='alert col-sm-12 alert-danger'><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Ocorreu um erro ao abrir turma.</div>");
        setTimeout(function () {
            $("#excluirTurma-alert").html('');
        }, 3000);
    });
}