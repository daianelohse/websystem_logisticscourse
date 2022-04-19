var tipoUser = $("input[name=tipo_user]").val();
//Se o tipo_user for 2 
var professorLogado = $("input[name=id_user]").val();


$(function () {
    $('#alunos').css("display", "inline");
    $('#alunos').css("display", "none");
    $("#gerenciarPermissoes").css("display","none");

    atualizarTurmas();
    atualizarNumeroSolicitacoesAcesso();

    if (tipoUser == 1) {
        var hora = new Date().getHours();
        if (hora >= 0 && hora <= 11) {
            $("#cumprimentoAdm").html("Bom dia,");
        }
        if (hora >= 12 && hora <= 17){
            $("#cumprimentoAdm").html("Boa tarde,");
        }
         if (hora >= 18 && hora <= 23){
            $("#cumprimentoAdm").html("Boa noite,");
        }
    }
});


function mensagem(id, mensagem) {
    $("#erro" + id + "").css("display", "block");
    $("#erro" + id + "").html("<br /><span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> " + mensagem + "");
    $("#formCadastro #" + id + "").css("border-color", "red");
}

function limpaMensagem(id) {
    $("#erro" + id + "").css("display", "none");
    $("#erro" + id + "").html("");
    $("#formCadastro #" + id + "").css("border-color", "#ccc");
}

function atualizarNumeroSolicitacoesAcesso() {
    ALERT("ENTROU");
//Se for administrador, mostrar o número de solicitações de acesso ao sistema
if (tipoUser == 1) {
    $.ajax({
        type: "GET",
        data: {url: "pegarNumeroSolicitacoesAcesso", pesquisa: "0"},
        url: "funcoesControle.php"
    }).done(function (result) {
        if (result != '') {
            if (parseInt(result) > 1 || parseInt(result) === 0) {
                $("#solicit").html("solicitações");
            } else {
                $("#solicit").html("solicitação");
            }

            $("#numeroSolicitacoesAcesso").html(result);
        }
    }).fail(function () {
        $("#listaTurmas").html("<div id='alert' class='alert col-sm-12 alert-danger'>\n\
    <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span> Erro ao carregar os alunos da turma, tente novamente. Se o erro persistir, consulte o administrador.</div>");

    });

}
}