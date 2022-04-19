<?php

session_start();

if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    header('Location:index.html');
} else {

    $_SESSION['id_usuario'] = null;
    $_SESSION['tipo_usuario'] = null;
    $_SESSION['nome_usuario'] = null;

    if (session_destroy()) {
        header('Location:index.html');
    }
}