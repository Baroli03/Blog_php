<?php

session_start();


// BASE_URL precisa ser '/Blog_php/' para corresponder à sua estrutura de pasta
define('BASE_URL', '/Blog_php/'); 

require_once __DIR__ . "/controller/PostController.php";
require_once __DIR__ . "/controller/AdminController.php"; 
require_once __DIR__ . "/controller/AuthController.php";


$rota = $_GET['p'] ?? '';
$rota = trim($rota, '/');
$partes = explode('/', $rota);

// --- NOVO BLOCO DE ROTEAMENTO PERSONALIZADO PARA /id/{numero} ---
if (isset($partes[0]) && $partes[0] === 'id' && isset($partes[1])) {
    PostController::mostrar($partes[1]);
} else {
    // --- Lógica de roteamento padrão para recurso/acao/parametro ---
    $recurso = $partes[0] ?: 'posts'; 
    $acao = $partes[1] ?? 'index';    
    $parametro = $partes[2] ?? null;  

    match ($recurso) {
        'posts' => match ($acao) {
            'index'       => PostController::index($parametro ?? 1),
            'mostrar'     => PostController::mostrar($parametro),     
            'criar'       => PostController::create(),              
            'edit'        => PostController::edit($parametro),      
            'delete'      => PostController::delete($parametro),    
            'addComentario'  => PostController::addComentario($parametro),
            'aprovarComentario' => PostController::aprovarComentario($parametro), 
            default       => PostController::index(),              
        },

        'admin' => match ($acao) {
            'painel'      => AdminController::painel(),             
            'logout'      => AuthController::logout(),             
            'login'       => AuthController::login(),               
            default       => AdminController::painel(), 
        },

        default => PostController::index(), 
    };
}