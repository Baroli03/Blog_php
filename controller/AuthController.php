<?php
require_once __DIR__ . '/../model/User.php';

class AuthController
{
    public static function login() 
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            validar_csrf_token(); 
            $nome = $_POST['nome_usuario'] ?? '';
            $senha = isset($_POST['senha']) ? $_POST['senha'] : ''; 

            $admin = User::getByUsername($nome); 

            if ($admin && password_verify($senha, $admin['senha_hash'])) {
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
        if (session_status() == PHP_SESSION_NONE) { 
            session_start();
        }
        session_destroy();
        header("Location: " . BASE_URL . "posts/index");
        exit;
    }
}
