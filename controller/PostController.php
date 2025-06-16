<?php
require_once __DIR__ . "/../model/Post.php";
require_once __DIR__ . "/../model/Comentario.php"; 

class PostController{


    private static function checkAdminLogin() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_id'])) {
            $_SESSION['error_message'] = 'Acesso restrito. Faça login como administrador.';
            header('Location: ' . BASE_URL . 'admin/login'); 
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
        while($row = $posts_brutos->fetch()) { 
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
    ];

    extract($data_para_view); 

    include __DIR__ . '/../view/partes/header.php';
    include __DIR__ . '/../view/posts/index.php'; 
    include __DIR__ . '/../view/partes/footer.php';
    }


    static function mostrar($parametro_id) {
        $id = (int)$parametro_id;
        $post = null;
        $titulo_pagina = 'Post Não Encontrado';
        $comentarios = []; 

        if ($id > 0) {
            $post = Post::pegarPostId($id); 
            if ($post) {
                $titulo_pagina = $post->titulo; 

                $is_admin_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['admin_id']);
                $comentarios = Comentario::listarComentariosPorPost($id, !$is_admin_logged_in); 
            }
        }
        
        $data_para_view = [
            'post' => $post,
            'titulo_pagina' => $titulo_pagina,
            'comentarios' => $comentarios, 
            'is_admin_logged_in' => $is_admin_logged_in ?? false 
        ];

        extract($data_para_view);

        include __DIR__ . '/../view/partes/header.php';
        include __DIR__ . '/../view/posts/mostrar.php';
        include __DIR__ . '/../view/partes/footer.php';
    }


    static function delete($parametro_id) {
        self::checkAdminLogin();

        $id = (int)$parametro_id;

        if ($id > 0) {
            $sucesso = Post::deletarPost($id);
            if ($sucesso) {
                $_SESSION['success_message'] = 'Post excluído com sucesso!';
                header('Location: ' . BASE_URL . 'posts/index');
                exit();
            } else {
                $_SESSION['error_message'] = 'Erro ao excluir o post. Por favor, tente novamente.';
                header('Location: ' . BASE_URL . 'posts/index');
                exit();
            }
        } else {
             $_SESSION['error_message'] = 'ID de post inválido para exclusão.';
             header('Location: ' . BASE_URL . 'posts/index');
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
            validar_csrf_token(); 
        
            $titulo = $_POST['titulo'] ?? '';
            $conteudo = $_POST['conteudo'] ?? '';
            $comentario_autor = $_POST['comentario_autor'] ?? '';
            $admin_id = $_SESSION['admin_id'] ?? null;

            if (empty($titulo) || empty($conteudo) || $admin_id === null) {
                $_SESSION['error_message'] = 'Erro: Título e Conteúdo são obrigatórios. Certifique-se de estar logado.';
                header('Location: ' . BASE_URL . 'posts/criar');
                exit();
            }

            $sucesso = Post::criarPost($admin_id, $titulo, $conteudo, $comentario_autor);

            if ($sucesso) {
                $_SESSION['success_message'] = 'Post criado com sucesso!';
                header('Location: ' . BASE_URL . 'posts/index');
                exit();
            } else {
                $_SESSION['error_message'] = 'Erro interno ao criar o post. Tente novamente.';
                header('Location: ' . BASE_URL . 'posts/criar');
                exit();
            }

        } else { 
            extract($data_para_view);

            include __DIR__ . '/../view/partes/header.php';
            include __DIR__ . '/../view/posts/criar.php'; 
            include __DIR__ . '/../view/partes/footer.php';
        }
    }

    static function edit($parametro_id) {
        self::checkAdminLogin(); 

        $id = (int)$parametro_id;
        $post = null; 
        $titulo_pagina = 'Editar Post';


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            validar_csrf_token(); 

            $titulo = $_POST['titulo'] ?? '';
            $conteudo = $_POST['conteudo'] ?? '';
            $comentario_autor = $_POST['comentario_autor'] ?? '';
            
            $post_id_para_atualizar = $id; 

            if ($post_id_para_atualizar <= 0 || empty($titulo) || empty($conteudo)) {
                $_SESSION['error_message'] = 'Erro: ID do post, Título e Conteúdo são obrigatórios para atualização.';
                header('Location: ' . BASE_URL . 'posts/editar/' . htmlspecialchars($post_id_para_atualizar)); 
                exit();
            }

            $sucesso = Post::atualizarPost($post_id_para_atualizar, $titulo, $conteudo, $comentario_autor); 

            if ($sucesso) {
                $_SESSION['success_message'] = 'Post atualizado com sucesso!';
                header('Location: ' . BASE_URL . 'posts/index'); 
                exit();
            } else {
                $_SESSION['error_message'] = 'Erro interno ao atualizar o post. Tente novamente.';
                header('Location: ' . BASE_URL . 'posts/edit/' . htmlspecialchars($post_id_para_atualizar));
                exit();
            }

        } else { 
            if ($id > 0) {
                $post = Post::pegarPostId($id); 
                if ($post) {
                    $titulo_pagina = 'Editar: ' . htmlspecialchars(substr($post->titulo, 0, 50)) . '...';
                } else {
                    $_SESSION['error_message'] = 'Post não encontrado para edição.';
                    header('Location: ' . BASE_URL . 'posts/index'); 
                    exit();
                }
            } else {
                $_SESSION['error_message'] = 'ID de post inválido para edição.';
                header('Location: ' . BASE_URL . 'posts/index'); 
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

            include __DIR__ . '/../view/partes/header.php';
            include __DIR__ . '/../view/posts/editar.php'; 
            include __DIR__ . '/../view/partes/footer.php';
        }
    }

    public static function addComentario($parametro_post_id) {
        $post_id = (int)$parametro_post_id;
        if ($post_id <= 0) {
            $_SESSION['error_message'] = 'Post inválido para adicionar comentário.';
            header('Location: ' . BASE_URL . 'posts/index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            validar_csrf_token(); 
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $conteudo = $_POST['conteudo'] ?? '';

            if (empty($nome) || empty($conteudo)) {
                $_SESSION['error_message'] = 'Nome e comentário são obrigatórios.';
            } else {
                $sucesso = Comentario::adicionarComentario($post_id, $nome, $email, $conteudo);
                if ($sucesso) {
                    $_SESSION['success_message'] = 'Comentário enviado com sucesso! Aguardando aprovação.';
                } else {
                    $_SESSION['error_message'] = 'Erro ao enviar comentário. Tente novamente.';
                }
            }
        }
        header('Location: ' . BASE_URL . 'id/' . htmlspecialchars($post_id));
        exit();
    }

    public static function aprovarComentario($parametro_comentario_id) {
        self::checkAdminLogin(); 
        $comentario_id = (int)$parametro_comentario_id;
        if ($comentario_id <= 0) {
            $_SESSION['error_message'] = 'ID de comentário inválido.';
            header('Location: ' . BASE_URL . 'posts/index'); 
            exit();
        }

        $comentario_data = Comentario::listarComentariosPorPost($comentario_id, false); 
        $post_id_redirect = null;
        if ($comentario_data && isset($comentario_data[0]) && isset($comentario_data[0]->post_id)) {
             $post_id_redirect = $comentario_data[0]->post_id;
        }

        $sucesso = Comentario::aprovarComentario($comentario_id);

        if ($sucesso) {
            $_SESSION['success_message'] = 'Comentário aprovado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao aprovar comentário. Tente novamente.';
        }

        if ($post_id_redirect) {
            header('Location: ' . BASE_URL . 'id/' . htmlspecialchars($post_id_redirect));
        } else {
            header('Location: ' . BASE_URL . 'posts/index'); 
        }
        exit();
    }
}