/**
 * Abrir jogos dinâmicamente na página princial / Cronometra o tempo jogado
 * Pesquisa dos jogos
 */

var tipoUser = $("input[name=tipo_user]").val();
var idUser = $("input[name=id_user]").val();

var horaAbriuJogo;
var tempoDecorridoJogo = new Date();

function abrirJogo(id) {

    $.ajax({
        type: "GET",
        url: "modalJogo.php?url=" + id
    }).done(function (result) {
        $("#modalJogo").html(result);
        if (tipoUser == 3) {
            horaAbriuJogo = new Date().getTime();
        }

        if (id === "17" || id === 17 && tipoUser == 1 || tipoUser == 2) {
            $('#botaoAbreResposta').show();
        }

    }).fail(function () {
        $("#modalJogo").html("Erro ao carregar o jogo. Tente novamente mais tarde. \n\
                                Se o erro persistir, comunique o professor ou o administrador");
    });
}

var estaAbertaResposta = false;
function verRespostaJogo(id) {

    if (id === "17" || id === 17 && tipoUser == 1 || tipoUser == 2) {
        if (!estaAbertaResposta) {
            estaAbertaResposta = true;
            $('#respostaNaviosNoPorto').show();
            $('#respostaNaviosNoPorto img').attr("src", 'dist/img/jogos/naviosNoPortoResposta.jpg');
            $('#botaoAbreResposta').html("FECHA RESPOSTA");

        } else {
            estaAbertaResposta = false;
            $('#respostaNaviosNoPorto').hide();
            $('#respostaNaviosNoPorto img').attr("src", '');
            $('#botaoAbreResposta').html("VER RESPOSTA");
        }
    }
}

$('#modalTodosJogos').on('hidden.bs.modal', function () {
    var id = $("#idJogo").val();
    fechaJogo(id);
});

function fechaJogo(id) {
    if (tipoUser == 3) {
        var agora = new Date().getTime();
        tempoDecorridoJogo = agora - horaAbriuJogo;
        

        //Se o aluno ficou mais de 15s no jogo, o registro é gravado banco
        if (tempoDecorridoJogo > 15000) {
            // tirar os milisegundos
            tempoDecorridoJogo /= 1000;

            // Pega os segundos 
            var segundos = Math.round(tempoDecorridoJogo % 60);

            // Remover os segundos da data
            tempoDecorridoJogo = Math.floor(tempoDecorridoJogo / 60);

            // Pega os minutos
            var minutos = Math.round(tempoDecorridoJogo % 60);

            // Remove os minutos da data
            tempoDecorridoJogo = Math.floor(tempoDecorridoJogo / 60);

            // Pega as horas
            var horas = Math.round(tempoDecorridoJogo % 24);

            // Remove as horas da data
            tempoDecorridoJogo = Math.floor(tempoDecorridoJogo / 24);


            var tempoDecorridoFinal = horas + ":" + minutos + ":" + segundos;

            $.ajax({
                type: "POST",
                data: {id_jogo: id, id_aluno: null, id_usuario: idUser, id_turma: null,
                    tempo: tempoDecorridoFinal, data: new Date().getDate()},
                url: "funcoesControle.php?url=salvarRelatorio"
            }).done(function (result) {
                if (result != '') {
                    alert("Erro ao salvar informações sobre sua atividade. Por favor, comunique seu professor ou o administrador.");
                }
            }).fail(function () {
                alert("Erro ao salvar informações sobre sua atividade. Por favor, comunique seu professor ou o administrador.");
            });
        }
    }
}

function pesquisar(pesquisa) {
    if (pesquisa !== '') {
        $(".breadcrumb").show();
        $(".breadcrumb").html('<a href="" target="_parent">Voltar</a>');
        $('.pagina').html("Carregando...");
        $.ajax({
            type: "GET",
            data: {url: "pesquisarJogos", pesquisa: pesquisa},
            url: "funcoesControle.php"
        }).done(function (result) {
            $('.pagina').html(result);
        }).fail(function () {
            alert("Desculpe, ocorreu um erro durante a pesquisa");
        });
    }
}

function planoDeAula(id) {
    // Atualizar breadcrumbs
    if ($("#bread").find("#breadPlanoDeAula").size() == 0) {
        $(".breadcrumb").append('<li id="breadPlanoDeAula"><a href="#" onclick="planoDeAula(' + id + ')">Plano de Aula</a></li>');
        $(".breadcrumb li:not(last-child) a").removeClass("active");
        $(".breadcrumb li:last-child a").addClass("active");
    }

    // Ocultar alunos, mostrar os jogos
    $("#alunos").hide();
    $("#planoDeAula").show();

    atualizarJogosLiberados(id);
    atualizarJogosBloqueados(id);

}

function atualizarJogosLiberados(id) {
    // Fazer uma consulta para pegar os jogos liberados da turma
    $.ajax({
        type: "GET",
        data: {url: "pegarJogosLiberados", turma: id},
        url: "funcoesControle.php"
    }).done(function (result) {
        $('#jogosLiberados').html(result);
    }).fail(function () {
        $(".planoDeAula-alert").modal('show');
        $('#erroPlanodeAula').html('Ocorreu um erro ao atualizar os jogos liberados.');
    });
}

function atualizarJogosBloqueados(id) {
    // Fazer uma consulta para pegar os jogos bloqueados da turma
    $.ajax({
        type: "GET",
        data: {url: "pegarJogosBloqueados", turma: id},
        url: "funcoesControle.php"
    }).done(function (result) {
        $('#jogosBloqueados').html(result);
    }).fail(function () {
        $(".planoDeAula-alert").modal('show');
        $('#erroPlanodeAula').html('Ocorreu um erro ao atualizar os jogos bloqueados.');
    });
}


function bloquearJogo(idJogo, idTurma) {
    $.ajax({
        type: "POST",
        data: {jogo: idJogo, turma: idTurma},
        url: "funcoesControle.php?url=bloquearJogo"
    }).done(function (result) {
        if (result == 'false') {
            $(".planoDeAula-alert").modal('show')
            $('#erroPlanodeAula').html('Ocorreu um erro ao bloquear o jogo.');
        } else {
            atualizarJogosLiberados(idTurma);
            atualizarJogosBloqueados(idTurma);
        }
    }).fail(function () {
        $(".planoDeAula-alert").modal('show')
        $('#erroPlanodeAula').html('Ocorreu um erro ao bloquear o jogo.');
    });
}

function liberarJogo(idJogo, idTurma) {
    $.ajax({
        type: "POST",
        data: {jogo: idJogo, turma: idTurma},
        url: "funcoesControle.php?url=liberarJogo"
    }).done(function (result) {
        if (result == 'false') {
            $(".planoDeAula-alert").modal('show');
            $('#erroPlanodeAula').html('Ocorreu um erro ao liberar o jogo.');
        } else {
            atualizarJogosLiberados(idTurma);
            atualizarJogosBloqueados(idTurma);
        }
    }).fail(function () {
        $(".planoDeAula-alert").modal('show');
        $('#erroPlanodeAula').html('Ocorreu um erro ao liberar o jogo.');
    });
}

function rabiscarLinhasJogo(id, tipo) {
    var dicasSublinhadas = new Array();
    $("#" + id + " " + tipo).click(function () {
        var index = dicasSublinhadas.indexOf(this);
        if (index < 0) {
            $(this).css("text-decoration", "line-through");
            dicasSublinhadas.push(this);
        } else {
            $(this).css("text-decoration", "none");
            dicasSublinhadas.splice(index, 1);
        }
    });
}
