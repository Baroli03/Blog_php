<?php
require_once __DIR__ . "/../model/Post.php";


class PostController{

    static function index($parametro = 1) {
    $paginaAtual = (int)$parametro;
    if ($paginaAtual < 1) {
        $paginaAtual = 1;
    }
    $postsPorPagina = 5;
    $offset = ($paginaAtual - 1) * $postsPorPagina;
    $posts_brutos = Post::listarPosts($postsPorPagina, $offset);

    $posts = [];

    if ($posts_brutos) {
        while($row = $posts_brutos->fetch_object()) {
            $posts[] = $row;
        }
    }

    $totalPosts = Post::contarTodosPosts();
    $totalPaginas = ceil($totalPosts / $postsPorPagina);


    include __DIR__ . '/../views/partes/header.php';
    include __DIR__ . '/../views/posts/index.php'; 
    include __DIR__ . '/../views/partes/footer.php';
    }

}

?>