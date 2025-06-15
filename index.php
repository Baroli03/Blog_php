<?php

session_start();


// CORREÇÃO CRÍTICA AQUI: BASE_URL precisa ser '/Blog_php/' para corresponder à sua estrutura de pasta
define('BASE_URL', '/Blog_php/'); 

require_once __DIR__ . "/controller/PostController.php";
require_once __DIR__ . "/controller/AdminController.php"; 
require_once __DIR__ . "/controller/AuthController.php";


$rota = $_GET['p'] ?? '';
$rota = trim($rota, '/');
$partes = explode('/', $rota);

// --- NOVO BLOCO DE ROTEAMENTO PERSONALIZADO PARA /id/{numero} ---
// Este bloco deve vir ANTES do seu 'match' principal
if (isset($partes[0]) && $partes[0] === 'id' && isset($partes[1])) {
    // Se a URL for Blog_php/id/X, chama diretamente o método mostrar do PostController
    PostController::mostrar($partes[1]);
} else {
    // --- Lógica de roteamento padrão para recurso/acao/parametro (SE NÃO FOR 'id/{numero}') ---
    $recurso = $partes[0] ?: 'posts'; 
    $acao = $partes[1] ?? 'index';    
    $parametro = $partes[2] ?? null;  

    match ($recurso) {
        'posts' => match ($acao) {
            'index'       => PostController::index($parametro ?? 1),
            // 'mostrar' continua sendo uma opção (Blog_php/posts/mostrar/X),
            // mas o principal para ver posts agora é Blog_php/id/X
            'mostrar'     => PostController::mostrar($parametro),     
            'create'      => PostController::create(),              
            'edit'        => PostController::edit($parametro),      
            'delete'      => PostController::delete($parametro),    
            'addComment'  => PostController::addComment($parametro), 
            default       => PostController::index(),              
        },

        'admin' => match ($acao) {
            'painel'      => AdminController::painel(),             
            'logout'      => AuthController::logout(),             
            'login'       => AuthController::login(),               
            default       => AdminController::painel(), 
        },

        default => PostController::index(), // Rota padrão se nenhum recurso for especificado
    };
}
// --- FIM DO BLOCO DE ROTEAMENTO PERSONALIZADO ---