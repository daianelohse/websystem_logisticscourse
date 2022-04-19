$(function () {
    $("#addAluno-alert").hide();

});

var idTurma;
var nomeTurma;
var alunosSelecionados;

function abrirAlunosTurma(id_turma, nome_turma, tipo) {

    $("#adicionarAluno").hide();

    if (tipo == "arquivada") {
        $("#btPlanodeAula").hide();
    } else {
        $('#btPlanodeAula').show();
    }


    if ($('#adicionarTurma').is(':visible')) {
        $('#adicionarTurma').hide();
    }

    idTurma = id_turma;
    nomeTurma = nome_turma;
    $("#turmas").hide();

    pegarUsuariosCadastro();

    if ($("#bread").find("#breadPlanoDeAula").size() > 0) {
        $(".breadcrumb li:last-child a").removeClass("active");
        $(".breadcrumb li:last-child").remove();
        $(".breadcrumb li:last-child a").addClass("active");
        $("#planoDeAula").hide();
    }


    if ($("#bread").find("#breadAluno").size() == 0) {
        $(".breadcrumb").append('<li id="breadAluno"><a href="#" onclick="abrirAlunosTurma(' + id_turma + ",'" + nome_turma + "','" + tipo + "'" + ')">Alunos (' + nome_turma + ')</a></li>');
        $(".breadcrumb li:not(last-child) a").removeClass("active");
        $(".breadcrumb li:last-child a").addClass("active");
    }



    $.ajax({
        type: "GET",
        data: {url: "pegarAlunosTurma", turma: id_turma},
        url: "funcoesControle.php"
    }).done(function (result) {
        $("#listaAlunos").html(result);
    }).fail(function () {
        $("#listaTurmas").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar os alunos da turma, tente novamente. Se o erro persistir, consulte o administrador.</div>");

    });

    $("#alunos").show();
    $("#nomeTurma").html(nome_turma);
}

$("#abreModalRelatorio").click(function () {
    abrirRelatorioTurma(idTurma);
});

function salvarAlunos() {
    var numerosUsuarios;
    if (alunosSelecionados.length > 0) {
        $("#addAluno-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Salvando...');
        $.ajax({
            type: "POST",
            data: {alunos_id_aluno: alunosSelecionados, turmas_id_turma: idTurma},
            url: "funcoesControle.php?url=salvarAlunos&turma=" + idTurma
        }).done(function (result) {
            $("#addAluno-alert").show().html(result);
            setTimeout(function () {
                $("#addAluno-alert").hide().html('');
                inserirAlunos();
                abrirAlunosTurma(idTurma, nomeTurma);
            }, 2000);
        }).fail(function () {
            $("#addAluno-alert").show().html('<div id="alerta" class="alert alert-danger"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i><span style="font-size:15pt">Erro ao realizar o cadastro da turma!</span> <br />Se o erro persistir, entre em contato com o administrador do sistema.</div>');
        });
    } else {
        $("#addAluno-alert").show().html('<div id="alerta" class="alert alert-danger">\n\
                                            <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>\n\
                                            Nenhum aluno foi selecionado.</div>');
    }
}

function inserirAlunos() {
    $("#adicionarAluno").show();
}

function pegarUsuariosCadastro() {
    $(".token-input-list").remove();
    $("#cadastrarAluno").tokenInput("funcoesControle.php?url=pegarAlunosSemTurmaPesquisa&idTurma=" + idTurma, {
        propertyToSearch: "nome_usuario",
        searchingText: "Pesquisando...",
        hintText: "Pesquise o nome de um aluno...",
        noResultsText: "Nenhum aluno encontrado",
        resultsFormatter: function (item) {
            return "<li>" + "<div id='pesquisaProfessores' class='glyphicon glyphicon-user' title='" + item.nome_usuario + "'></div>" +
                    "<div style='display: inline-block; padding-left: 10px;'><div class='full_name'>" + item.nome_usuario + "</div><div class='email'>" + item.email_usuario + "</div></div></li>";
        },
        tokenFormatter: function (item) {
            return "<span id='professorSelecionado'>" + item.nome_usuario + "</span>";
        },
        onAdd: function (item) {
            if (alunosSelecionados.indexOf(item.id_aluno) > -1) {
                return false;
            } else {
                alunosSelecionados.push(item.id_aluno);
            }
        },
        onDelete: function (item) {

            var index = alunosSelecionados.indexOf(item.id_aluno);
            if (index > -1) {
                alunosSelecionados.splice(index, 1);
            }
        },
        onReady: function () {
            alunosSelecionados = new Array();
        }
    });
}

function removerAlunoTurma(id_turma, id_aluno, nome) {
    $.ajax({
        type: "GET",
        data: {id_turma: id_turma, id_aluno: id_aluno, nome: nome},
        url: "modalRemoverAlunoTurma.php"
    }).done(function (result) {
        $("#modalRemoverAluno").html(result);
    }).fail(function () {
        $("#modalRemoverAluno").show().html('<div id="alerta" class="alert alert-danger">\n\
                                            <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>\n\
                                            Erro ao remover aluno da turma.</div>');
    });
}

function confirmarRemoverAluno(id_turma, id_aluno) {
    $("#removerAluno-alert").show().html('<div id="alerta" class="alert alert-info"><i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i> Removendo...');
    $.ajax({
        type: "POST",
        data: {id_turma: id_turma, id_aluno: id_aluno},
        url: "funcoesControle.php?url=removerAlunoTurma"
    }).done(function (result) {
        $("#removerAluno-alert").show();
        $("#removerAluno-alert").html(result);

        if ($('#alerta').hasClass('alert-success')) {
            $(".botoesModalConfirmacao").hide();
        }

        setTimeout(function () {
            if ($('#alerta').hasClass('alert-success')) {
                abrirAlunosTurma(idTurma, nomeTurma);
                atualizarNumeroTurmasArquivadas();
                $("#removerAluno-alert").html('');
                $("#removerAluno-alert").hide();
                $('.modalRemoverAluno').modal('hide');
            }

        }, 2000);
    }).fail(function () {
        $("#removerAluno-alert").show();
        $("#removerAluno-alert").html('<div id="alerta" class="alert alert-danger">\n\
                                            <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>\n\
                                            Erro ao remover aluno da turma.</div>');
        setTimeout(function () {
            $("#removerAluno-alert").html('');
        }, 3000);

    });
}
