<?php
require_once __DIR__ . "/../model/Post.php";


class PostController{

    static function index($numero_pagina = 1) {
    $paginaAtual = (int)$numero_pagina;
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

    $data_para_view = [
            'posts' => $posts,
            'paginaAtual' => $paginaAtual, 
            'totalPaginas' => $totalPaginas,
        ]

    extract($data_para_view); 

    include __DIR__ . '/../views/partes/header.php';

    include __DIR__ . '/../views/posts/index.php'; 
    
    include __DIR__ . '/../views/partes/footer.php';
    }


    static function mostrar($parametro_id) {
        $id = (int)$parametro_id;
        if ($id < 1) {

            $post = null; 
        } else {
            $post = Post::pegarPostId($id);
        }

        include __DIR__ . '/../views/partes/header.php';

        include __DIR__ . '/../views/posts/mostrar.php';

        include __DIR__ . '/../views/partes/footer.php';
    }



    static function delete($parametro_id) {
        $id = (int)$parametro_id;

        $sucesso = Post::deletarPost($id);
        if ($id > 0) {

            if ($sucesso) {
                $_SESSION['success_message'] = 'Post excluído com sucesso!';
                header('Location: /posts/index');
                exit();
            } else {
                $_SESSION['error_message'] = 'Erro ao excluir o post. Por favor, tente novamente.';
                header('Location: /posts/index');
                exit();
            }
        } else {
             header('Location: /posts/index');
            exit();
    }
}


}

?>