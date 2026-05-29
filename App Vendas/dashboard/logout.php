<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// limpar sessão
$_SESSION = [];

// remover cookie da sessão
if (ini_get('session.use_cookies')) {

    $params = session_get_cookie_params();

    setcookie(

        session_name(),

        '',

        time() - 42000,

        $params['path'],

        $params['domain'],

        $params['secure'],

        $params['httponly']

    );

}

// destruir sessão
session_destroy();

// redirecionar
header('Location: /');

exit;