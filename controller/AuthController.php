<?php
require_once __DIR__ . '/../model/User.php';

class AuthController
{
    public static function login() 
    {
 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome_usuario'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $admin = User::getByUsername($nome);

            if ($admin && password_verify($senha, $admin['senha'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                header("Location: " . BASE_URL . "admin/painel");
                exit;
            } else {
                $_SESSION['error_message'] = "Usuário ou senha inválidos.";
                
                $erro = $_SESSION['error_message']; 
                include __DIR__ . '/../view/partes/header.php';
                include __DIR__ . '/../view/admin/login.php'; 
                include __DIR__ . '/../view/partes/footer.php';
                unset($_SESSION['error_message']); 
                exit;
            }
        } else {
            $erro = $_SESSION['error_message'] ?? null; 
            unset($_SESSION['error_message']);
            include __DIR__ . '/../view/partes/header.php';
            include __DIR__ . '/../view/admin/login.php'; 
            include __DIR__ . '/../view/partes/footer.php';
            exit; 
        }
    }

    public static function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "posts/index");
        exit;
    }
}