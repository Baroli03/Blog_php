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
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: /Blog_php/admin");
                exit;
            } else {
                $erro = "Usuário ou senha inválidos.";
                include __DIR__ . '/../view/admin/login.php';
            }
        } else {
            $erro = '';
            include __DIR__ . '/../view/admin/login.php';
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /Blog_php/login");
        exit;
    }
}
