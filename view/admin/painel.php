<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Redireciona usando BASE_URL, para garantir que o acesso restrito leve ao login correto
    header("Location: " . BASE_URL . "admin/login");
    exit;
}
?>

<h1>Bem-vindo, Admin!</h1>
<p>Você está autenticado.</p>
<p>
    <a href="<?= BASE_URL ?>admin/logout">Sair</a> </p>
<hr>
<h2>Ações Administrativas:</h2>
<ul>
    <li><a href="<?= BASE_URL ?>posts/create">Criar Novo Post</a></li>
    <li><a href="<?= BASE_URL ?>posts/index">Gerenciar Posts (Ver lista completa)</a></li>
</ul>