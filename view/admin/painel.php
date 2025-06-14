<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: /login");
    exit;
}
?>

<h1>Bem-vindo, Admin!</h1>
<p>Você está autenticado.</p>
<p>
    <a href="/admin/logout">Sair</a> </p>
<hr>
<h2>Ações Administrativas:</h2>
<ul>
    <li><a href="/posts/create">Criar Novo Post</a></li>
    <li><a href="/posts/index">Gerenciar Posts (Ver lista completa)</a></li>
</ul>