<?php
session_start();

define('BASE_URL', '/Blog_php'); 

require_once __DIR__ . '/app/controller/AuthController.php';

$url = $_GET['url'] ?? '';

switch ($url) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'admin':
        if (!isset($_SESSION['admin_id'])) {
            header("Location: " . BASE_URL . "/login");
            exit;
        }
        include __DIR__ . '/app/view/admin/painel.php';
        break;

    default:
        include __DIR__ . '/app/view/public/index.php';
        break;
}