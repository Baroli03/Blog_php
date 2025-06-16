<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>MeuBlog</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css">
</head>
<body>
    
    <nav class="main-nav">
    <ul>
            <li><a href="<?= BASE_URL ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>posts/index">Ver Todos os Posts</a></li>
            
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['admin_id'])) : ?>
                <li><a href="<?= BASE_URL ?>posts/criar">Criar Novo Post</a></li>
                <li><a href="<?= BASE_URL ?>admin/painel">Painel Admin</a></li>
                <li><a href="<?= BASE_URL ?>admin/logout">Sair</a></li>
            <?php else : ?>
                <li><a href="<?= BASE_URL ?>admin/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
            
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) { 
        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>
<main>
    
