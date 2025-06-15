<?php
require_once __DIR__ . '/../model/User.php';

class AuthController
{
    public function login()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome_usuario'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $admin = User::getByUsername($nome);

            if ($admin && password_verify($senha, $admin['senha'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                // CORREÇÃO AQUI: Redirecionamento corrigido com BASE_URL
                header("Location: " . BASE_URL . "admin/painel");
                exit;
            } else {
                $_SESSION['error_message'] = "Usuário ou senha inválidos.";
                // CORREÇÃO AQUI: Redirecionamento corrigido com BASE_URL
                // Alterado para admin/login para voltar para a página de login em caso de erro
                header("Location: " . BASE_URL . "admin/login"); 
                exit;
            }
        } else {
            // CORREÇÃO AQUI: Redirecionamento corrigido com BASE_URL
            // Alterado para admin/login se não for POST, para exibir o formulário de login
            header("Location: " . BASE_URL . "admin/login"); 
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        // CORREÇÃO AQUI: Redirecionamento corrigido com BASE_URL
        header("Location: " . BASE_URL . "posts/index");
        exit;
    }
}