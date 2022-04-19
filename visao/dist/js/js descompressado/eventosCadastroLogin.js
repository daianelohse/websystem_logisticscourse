erroLogin = false;
var usuarioCadastroEdicao = null;


function logar() {

    var userLogin = $("#login-username").val();
    var senhaLogin = $("#login-password").val();

    if (userLogin === '') {
        mensagem("userlogin", "Informe o seu usuário ou o seu email!");
        erroLogin = true;
    }

    if (senhaLogin === '') {
        mensagem("senhalogin", "Informe a sua senha!");
        erroLogin = true;
    }

    if (!erroLogin) {


        $.ajax({
            type: "POST",
            data: {user_login: userLogin, senha_login: senhaLogin},
            url: "validacaoLogin.php"
        }).done(function (result) {
            $("#login-alert").css('display', 'inline');
            $("#login-alert").html(result);
            if ($('#alerta').hasClass('alert-success')) {
                window.location.href = "jogos.php";
            }
        });
    }
}

/**
 * CADASTRO
 */

erro = false;

function cadastrar(acao, id, autorEdicao) {

    erro = false;

    var nome = $('#signupform #nome').val();
    var user = $('#signupform #usuario').val();
    var email = $('#signupform #email').val();
    var senha = $('#signupform #senha').val();
    var repetirSenha = $('#signupform #repetirSenha').val();
    var tipo = 2;

    // Informe o nome do usuário
    if (nome === '') {
        mensagem("nome", "Informe o seu nome completo!");
        erro = true;
    }
    
      if ($('#signupform #nome').val() === '' || $('#signupform #nome').val().length < 7 || $('#signupform #nome').val().indexOf(" ") <= 0) {
            mensagem("nome", "Informe o seu nome completo!");
            erro = true;
        } 


    // Informe o user do usuário
    if ($('#signupform #usuario').val() === '') {
        mensagem("usuario", "Informe o seu usuário!");
    } else {
        $.ajax({
            type: "POST",
            async: false,
            data: {id_usuario: id, user_usuario: $('#signupform #usuario').val()},
            url: "validacaoCadastro.php?url=validarUser"
        }).done(function (result) {
            if (result !== '') {
                mensagem("usuario", "Este usuário já está cadastrado!");
                erro = true;
            } else {
                limpaMensagem("usuario");
            }

        });
    }

    // Informe o email do usuário
    if ($('#signupform #email').val() === '') {
        mensagem("email", "Infome o seu email!");
        erro = true;
    } else {
        if (!validarEmail($('#signupform #email').val())) {
            mensagem("email", "Infome um e-mail válido!");
            erro = true;
        } else {
            $.ajax({
                type: "POST",
                async: false,
                data: {id_usuario: id, email_usuario: $('#signupform #email').val()},
                url: "validacaoCadastro.php?url=validarEmail"
            }).done(function (result) {

                if (result !== '') {
                    mensagem("email", "" + result + "");
                    erro = true;
                } else {
                    limpaMensagem("email");
                }

            });
        }
    }

    // Informe a senha do usuário
    if (senha === '' && repetirSenha != '') {
        mensagem("senha", "Infome uma senha!");
        erro = true;
    }

    // Repita a senha do usuário
    if (repetirSenha === '' && senha != '') {
        mensagem("repetirSenha", "Campo 'Repetir Senha' está vazio!");
        erro = true;
    }

    // Senha e repetir senha coincidem?
    if (senha !== '' && repetirSenha !== '' && senha !== repetirSenha) {
        mensagem("senha", "Os campos 'Senha' e 'Repetir Senha' não coincidem!");
        mensagem("repetirSenha", "Os campos 'Senha' e 'Repetir Senha' não coincidem!");
        erro = true;
    }



    if (acao != 'editar') {
        if (!$('#professorPerm').is(':checked') && !$('#alunoPerm').is(':checked')) {
            mensagem("tipo", "Informe seu tipo!");
            erro = true;
        } else {
            if ($('#alunoPerm').is(':checked')) {
                tipo = 3;
            }
        }
    }

    if (!erro) {

        limpaMensagem("nome");
        limpaMensagem("usuario");
        limpaMensagem("email");
        limpaMensagem("senha");
        limpaMensagem("repetirSenha");

        if (acao === 'cadastrar') {

            $.ajax({
                type: "POST",
                data: {nome_usuario: nome.toUpperCase(), user_usuario: user, email_usuario: email.toLowerCase(), senha_usuario: senha, tipo_usuario: tipo, estaAprovado: false},
                url: "validacaoCadastro.php?url=cadastrar"
            }).done(function (result) {
                $("#signup-alert").css('display', 'inline');
                $("#signup-alert").html(result);
                if ($('#alerta').hasClass('alert-success')) {
                    $('#signupform').find('input, textarea, button, select').each(function () {
                        $(this).prop('disabled', true);
                    });
                }

            });
        } else if (acao === 'editar') {
            $.ajax({
                type: "POST",
                data: {id_usuario: id, nome_usuario: nome.toUpperCase(), user_usuario: user, email_usuario: email.toLowerCase(), senha_usuario: senha, tipo_usuario: tipo, autor_edicao: autorEdicao},
                url: "funcoesControle.php?url=editarCadastroUsuario"
            }).done(function (result) {
                $("#signup-alert").css('display', 'inline');
                $("#signup-alert").html(result);
                if ($('#alerta').hasClass('alert-success')) {
                    $("#senha").val('');
                    $("#repetirSenha").val('');
                    setTimeout(function () {
                        $("#signup-alert").html('');
                    }, 3000);
                }

            });
        }
    }
}


function validarEmail(email) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(email)) {
        return true;
    } else {
        return false;
    }
}


$(function () {
    $('#nome').keypress(function (key) {
        if ((key.charCode < 97 || key.charCode > 122) && (key.charCode < 65 || key.charCode > 90) &&
                (key.charCode != 45) && (key.charCode != 0) && (key.charCode != 32)
                && (key.charCode != 225) && (key.charCode != 237) && (key.charCode != 243) && (key.charCode != 250)
                && (key.charCode != 226) && (key.charCode != 234) && (key.charCode != 244)) {
            return false;
        }
    });
//nome completo
    $("#nome").change(function () {

        if ($('#signupform #nome').val() === '' || $('#signupform #nome').val().length < 7 || $('#signupform #nome').val().indexOf(" ") <= 0) {
            mensagem("nome", "Informe o seu nome completo!");
        } else {
            limpaMensagem("nome");
        }

    });
//user
    $("#usuario").change(function () {

        // Informe o user do usuário
        if ($('#signupform #usuario').val() === '') {
            mensagem("usuario", "Informe o seu usuário!");
        } else {

            if (usuarioCadastroEdicao == null) {
                usuarioCadastroEdicao = "";
            }

            $.ajax({
                type: "POST",
                data: {id_usuario: usuarioCadastroEdicao, user_usuario: $('#signupform #usuario').val()},
                url: "validacaoCadastro.php?url=validarUser"
            }).done(function (result) {

                if (result !== '') {
                    mensagem("usuario", "Este usuário já está cadastrado!");
                    erro = true;
                } else {
                    erro = false;
                    limpaMensagem("usuario");
                }

            });
        }
    });


//Informe o email do usuário
    $("#email").change(function () {
        if ($('#signupform #email').val() === '') {
            mensagem("email", "Infome o seu email!");
            erro = true;
        } else {
            if (usuarioCadastroEdicao == null) {
                usuarioCadastroEdicao = "";
            }
            if (!validarEmail($('#signupform #email').val())) {
                mensagem("email", "Infome um e-mail válido!");
                erro = true;
            } else {
                $.ajax({
                    type: "POST",
                    data: {id_usuario: usuarioCadastroEdicao, email_usuario: $('#signupform #email').val()},
                    url: "validacaoCadastro.php?url=validarEmail"
                }).done(function (result) {
                    if (result !== '') {
                        mensagem("email", "" + result + "");
                        erro = true;
                    } else {
                        erro = false;
                        limpaMensagem("email");
                    }

                });
            }

        }

    });

//Informe a senha do usuário
    $("#senha").blur(function () {
        if ($('#signupform #senha').val() == '') {
            mensagem("senha", "Infome uma senha!");
        } else if ($('#signupform #senha').val() !== '' && $('#signupform #senha').val() !== $('#signupform #repetirSenha').val() && $('#signupform #repetirSenha').val() !== '') {
            mensagem("senha", "Os campos 'Senha' e 'Repetir Senha' não coincidem!");
            mensagem("repetirSenha", "Os campos 'Senha' e 'Repetir Senha' não coincidem!");
        } else {
            limpaMensagem("repetirSenha");
            limpaMensagem("senha");
        }
    });
// Repita a senha do usuário
    $("#repetirSenha").blur(function () {
        if ($('#signupform #repetirSenha').val() === '') {
            mensagem("repetirSenha", "Campo 'Repetir Senha' está vazio!");
        } else if ($('#signupform #senha').val() !== '' && $('#signupform #senha').val() !== $('#signupform #repetirSenha').val() && $('#signupform #repetirSenha').val() !== '') {
            mensagem("senha", "Os campos 'Senha' e 'Repetir Senha' não coincidem!");
            mensagem("repetirSenha", "Os campos 'Senha' e 'Repetir Senha' não coincidem!");
        } else {
            limpaMensagem("repetirSenha");
            limpaMensagem("senha");
        }
    });


    $('#professorPerm').change(function () {
        limpaMensagem("tipo");
    });

    $('#alunoPerm').change(function () {
        limpaMensagem("tipo");
    });


    //LOGIN
    $("#login-username").change(function () {
        if ($("#login-username").val() === '') {
            mensagem("userlogin", "Informe o seu usuário ou o seu email!");
        } else {
            limpaMensagem("userlogin");
        }
    });

    $("#login-password").change(function () {
        if ($("#login-password").val() === '') {
            mensagem("senhalogin", "Informe a sua senha!");
        } else {
            limpaMensagem("senhalogin");
        }
    });

});
