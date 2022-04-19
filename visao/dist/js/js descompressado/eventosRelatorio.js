var modalAberto = 0; //1 - Turma | 2 - Aluno
var turma;

function dataRelatorio(modal) {
    var data = new Date();
    var mes = (data.getMonth() - 1);
    var ano = data.getFullYear();


    if (mes <= 6) {
        $("#dataInicio" + modal).val('01/01/' + ano);
        $("#dataFim" + modal).val('30/06/' + ano);

    } else if (mes > 6) {
        $("#dataInicio" + modal).val('01/07/' + ano);
        $("#dataFim" + modal).val('31/12/' + ano);

    }
}

function abrirModalRelatorioTurma(id, nome) {

    modalAberto = 1;
    $.ajax({
        type: "GET",
        data: {id: id, nome: nome},
        url: "modalRelatorioTurma.php"
    }).done(function (result) {
        $("#modalRelatorioTurma").html(result);
        dataRelatorio('Turma');
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });

    }).fail(function () {
        $("#modalRelatorioTurma").html("<div class='alert alert-danger'>Desculpe, ocorreu um erro.</div>");
    });
}

function abrirModalRelatorioAluno(id, turma, nome) {

    this.turma = turma;
    modalAberto = 2;
    $.ajax({
        type: "GET",
        data: {id: id, nome: nome},
        url: "modalRelatorioAluno.php"
    }).done(function (result) {
        $("#modalRelatorioAluno").html(result);

        dataRelatorio('Aluno');
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });

    }).fail(function () {
        $("#modalRelatorioAluno").html("<div class='alert alert-danger'>Desculpe, ocorreu um erro.</div>");
    });
}

function preparaData() {
    dataInicio = dataInicio.getDate() + "/" + (dataInicio.getMonth() + 1) + "/" + dataInicio.getFullYear();
    dataFim = dataFim.getDate() + "/" + (dataFim.getMonth() + 1) + "/" + dataFim.getFullYear();
}


function gerarRelatorio() {


    limpaMensagem("dataInicio");
    limpaMensagem("dataFim");

    $("#relatorioTurma-alert").hide().html("");
    $("#relatorioAluno-alert").hide().html("");

    if (modalAberto == 1) {
        $("#statusTurma").show().html("Carregando...");
        var data1 = $("#dataInicioTurma").val();
        var data2 = $("#dataFimTurma").val();
    } else {
        $("#statusAluno").show().html("Carregando...");
        var data1 = $("#dataInicioAluno").val();
        var data2 = $("#dataFimAluno").val();
    }

    var dataUm = new Array();
    var dataDois = new Array();

    for (var i = 0; i < 3; i++) {
        var d1 = data1;
        var d2 = data2;
        if (i == 0) {
            dataUm[i] = d1.substring(i, (i + 2));
            dataDois[i] = d2.substring(i, (i + 2));
        } else {
            dataUm[i] = d1.substring(i * 3, (i * 5));
            dataDois[i] = d2.substring(i * 3, (i * 5));
        }
    }


    dataInicio = new Date(dataUm[2], dataUm[1] - 1, dataUm[0]);
    dataFim = new Date(dataDois[2], dataDois[1] - 1, dataDois[0]);


    var id;
    if (modalAberto == 1) {
        id = $("#idTurma").val();
    } else {
        id = $("#idAluno").val();
    }
    if (data1 == '' && data2 == '') {
        mensagem("dataInicio", "'data início' está vazio");
        mensagem("dataFim", "'data fim' está vazio");

    } else if (dataInicio == '' || dataInicio == 'undefined') {
        mensagem("dataInicio", "'data início' está vazio");
    } else if (dataFim == '' || dataFim == 'undefined') {
        mensagem("dataFim", "'data fim' está vazio");
    } else if (dataInicio > dataFim) {
        if (modalAberto == 1) {
            $("#relatorioTurma-alert").show().html("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Data Inválida<br />");
            $("#statusTurma").hide().html("");
        } else {
            $("#relatorioAluno-alert").show().html("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Data Inválida<br />");
            $("#statusAluno").hide().html("");
        }
    } else {
        preparaData();
        if (modalAberto == 1) {
            $.ajax({
                type: "GET",
                data: {url: "verificarResultadosRelatorioTurma", id: id, data_inicio: dataInicio, data_fim: dataFim},
                url: "funcoesControle.php"
            }).done(function (result) {

                if (result !== 'sucesso') {
                    $("#relatorioTurma-alert").css("display", "inline");
                    $("#relatorioTurma-alert").html("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Nenhum resultado encontrado durante o período informado.<br />");

                    $("#statusTurma").hide().html("");
                } else if (result == 'sucesso') {

                    $("#statusTurma").show().html("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Se nenhuma janela for aberta dinâmicamente com o pdf gerado, <a href='relatorioTurma.php?id=" + id + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "' target='_blank'>clique aqui </a>.");

                    window.open("relatorioTurma.php?id=" + id + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim, "_blank");
                }
            }).fail(function () {
                $("#modalRelatorioAluno").html("<div class='alert alert-danger'>Desculpe, ocorreu um erro ao verificar datas.</div>");
                preparaLink();
                $("#geraRelatorioTurma").attr("href", "relatorioTurma.php?id=" + id + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim);

            });

        } else {
            $.ajax({
                type: "GET",
                data: {url: "verificarResultadosRelatorioAluno", id: id, turma: turma, data_inicio: dataInicio, data_fim: dataFim},
                url: "funcoesControle.php"
            }).done(function (result) {
                if (result !== 'sucesso') {
                    $("#relatorioAluno-alert").css("display", "inline");
                    $("#relatorioAluno-alert").html("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Nenhum resultado encontrado durante o período informado.<br />");
                    $("#statusAluno").hide().html("");

                } else {
                    $("#statusAluno").show().html("<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Se nenhuma janela for aberta dinâmicamente com o pdf gerado, <a href='relatorioAluno.php?id=" + id + "&turma=" + turma + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "' target='_blank'>clique aqui</a>.");

                    window.open("relatorioAluno.php?id=" + id + "&turma=" + turma + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim);

                }
            }).fail(function () {
                $("#modalRelatorioAluno").html("<div class='alert alert-danger'>Desculpe, ocorreu um erro ao verificar datas.</div>");
                preparaLink();
                $("#geraRelatorioAluno").attr("href", "relatorioAluno.php?id=" + id + "&turma=" + turma + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim);

            });
            $("#statusAluno").hide().html("");
        }
    }
}
