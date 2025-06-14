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
                header("Location: /Blog_php/painel");
                exit;
            } else {
                $_SESSION['error_message'] = "Usuário ou senha inválidos.";
                header("Location: /posts/index");
                exit;
            }
        } else {
            header("Location: /posts/index");
            exit;
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /posts/index");
        exit;
    }
}
