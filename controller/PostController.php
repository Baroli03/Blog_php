<?php
require_once __DIR__ . "/../model/Post.php";


class PostController{


    private static function checkAdminLogin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_id'])) {
            $_SESSION['error_message'] = 'Acesso restrito. Faça login como administrador.';
            header('Location: /admin/login'); 
            exit();
        }
    }


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
        while($row = $posts_brutos->fetch(PDO::FETCH_OBJ)) {
            $posts[] = $row;
        }
    }


    $totalPosts = Post::contarTodosPosts();
    $totalPaginas = ceil($totalPosts / $postsPorPagina);

    $data_para_view = [
            'posts' => $posts,
            'paginaAtual' => $paginaAtual, 
            'totalPaginas' => $totalPaginas,
            'titulo_pagina' => 'Lista de Posts' 
<<<<<<< HEAD
        ]
=======
    ];
>>>>>>> baecbd1 (up)

    extract($data_para_view); 

    include __DIR__ . '/../views/partes/header.php';

    include __DIR__ . '/../views/posts/index.php'; 
    
    include __DIR__ . '/../views/partes/footer.php';
    }


    static function mostrar($parametro_id) {

        $id = (int)$parametro_id;
        $post = null;
        $titulo_pagina = 'Post Não Encontrado';
        if ($id > 0) {
            $post = Post::pegarPostId($id); 
            if ($post) {
                $titulo_pagina = $post->titulo; 
            }
        }
        
        $data_para_view = [
            'post' => $post,
            'titulo_pagina' => $titulo_pagina
        ];

        extract($data_para_view);

        include __DIR__ . '/../views/partes/header.php';

        include __DIR__ . '/../views/posts/mostrar.php';

        include __DIR__ . '/../views/partes/footer.php';
    }



    static function delete($parametro_id) {
        self::checkAdminLogin();

        $id = (int)$parametro_id;

        
        if ($id > 0) {
            $sucesso = Post::deletarPost($id);
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
             $_SESSION['error_message'] = 'ID de post inválido para exclusão.';
             header('Location: /posts/index');
            exit();
    }
}


static function create() {
        self::checkAdminLogin(); 

        $data_para_view = [
            'titulo_pagina' => 'Criar Novo Post', 
            'post' => null, 
            'success_message' => $_SESSION['success_message'] ?? null, 
            'error_message' => $_SESSION['error_message'] ?? null,   
        ];
    
        unset($_SESSION['success_message'], $_SESSION['error_message']); 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            $titulo = $_POST['titulo'] ?? '';
            $conteudo = $_POST['conteudo'] ?? '';
            $comentario_autor = $_POST['comentario_autor'] ?? '';
            $admin_id = $_SESSION['admin_id'] ?? null;

            if (empty($titulo) || empty($conteudo) || $admin_id === null) {
                $_SESSION['error_message'] = 'Erro: Título e Conteúdo são obrigatórios. Certifique-se de estar logado.';
                header('Location: /posts/create');
                exit();
            }


            $sucesso = Post::criarPost($admin_id, $titulo, $conteudo, $comentario_autor);

            if ($sucesso) {
                $_SESSION['success_message'] = 'Post criado com sucesso!';
                header('Location: /posts/index');
                exit();
            } else {
                $_SESSION['error_message'] = 'Erro interno ao criar o post. Tente novamente.';
                header('Location: /posts/create');
                exit();
            }

        } else {
            extract($data_para_view);

            include __DIR__ . '/../views/partes/header.php';
            include __DIR__ . '/../views/posts/create.php';
            include __DIR__ . '/../views/partes/footer.php';
        }
    }

    static function edit($parametro_id) {
        self::checkAdminLogin(); 

        $id = (int)$parametro_id;
        $post = null; 
        $titulo_pagina = 'Editar Post';


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $titulo = $_POST['titulo'] ?? '';
            $conteudo = $_POST['conteudo'] ?? '';
            $comentario_autor = $_POST['comentario_autor'] ?? '';
            
            if ($id <= 0 || empty($titulo) || empty($conteudo)) {
                $_SESSION['error_message'] = 'Erro: ID do post, Título e Conteúdo são obrigatórios.';
                header('Location: /posts/edit/' . htmlspecialchars($id)); 
                exit();
            }

            $sucesso = Post::atualizarPost($id, $titulo, $conteudo, $comentario_autor);

            if ($sucesso) {
                $_SESSION['success_message'] = 'Post atualizado com sucesso!';
                header('Location: /posts/index'); 
                exit();
            } else {
                $_SESSION['error_message'] = 'Erro interno ao atualizar o post. Tente novamente.';
                header('Location: /posts/edit/' . htmlspecialchars($id));
                exit();
            }

        } else { 
            if ($id > 0) {
                $post = Post::pegarPostId($id); 
                if ($post) {
                    $titulo_pagina = 'Editar: ' . htmlspecialchars(substr($post->titulo, 0, 50)) . '...';
                } else {
                    $_SESSION['error_message'] = 'Post não encontrado para edição.';
                    header('Location: /posts/index'); 
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'ID de post inválido para edição.';
                header('Location: /posts/index'); 
                exit();
            }

           
            $data_para_view = [
                'titulo_pagina' => $titulo_pagina,
                'post' => $post, 
                'success_message' => $_SESSION['success_message'] ?? null,
                'error_message' => $_SESSION['error_message'] ?? null,
            ];
            unset($_SESSION['success_message'], $_SESSION['error_message']); 
            extract($data_para_view); 

            include __DIR__ . '/../views/partes/header.php';
            include __DIR__ . '/../views/posts/edit.php'; 
            include __DIR__ . '/../views/partes/footer.php';
        }
    }

}

?>