<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_input() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function validar_csrf_token() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $_SESSION['error_message'] = 'Erro de validação (CSRF). Ação bloqueada por segurança.';
        unset($_SESSION['csrf_token']);
        header('Location: ' . BASE_URL);
        exit;
    }
    unset($_SESSION['csrf_token']);
}

define('BASE_URL', '/Blog_php/');

require_once __DIR__ . "/controller/PostController.php";
require_once __DIR__ . "/controller/AdminController.php";
require_once __DIR__ . "/controller/AuthController.php";


$rota = $_GET['p'] ?? '';
$rota = trim($rota, '/');
$partes = explode('/', $rota);

if (isset($partes[0]) && $partes[0] === 'id' && isset($partes[1])) {
    PostController::mostrar($partes[1]);
} else {
   
    $recurso = $partes[0] ?: 'posts'; 
    $acao = $partes[1] ?? 'index';    
    $parametro = $partes[2] ?? null;  

    match ($recurso) {
        'posts' => match ($acao) {
            'index'         => PostController::index($parametro ?? 1),
            'mostrar'       => PostController::mostrar($parametro),
            'criar'         => PostController::create(),
            'edit'          => PostController::edit($parametro),
            'delete'        => PostController::delete($parametro),
            'addComentario' => PostController::addComentario($parametro),
            'aprovarComentario' => PostController::aprovarComentario($parametro),
            default         => PostController::index(),
        },

        'admin' => match ($acao) {
            'painel'               => AdminController::painel(),
            'logout'               => AuthController::logout(),
            'login'                => AuthController::login(),
            'gerenciar-admins'     => AdminController::gerenciarAdmins(),     
            'processar-admin-form' => AdminController::processarAdminForm(), 
            default                => AdminController::painel(), 
        },

        default => PostController::index(), 
    };
}
