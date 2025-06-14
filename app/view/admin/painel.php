<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: /login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Área Administrativa</title>
</head>
<body>
    <h1>Bem-vindo, Admin!</h1>
    <p>Você está autenticado.</p>
    <a href="logout.php">Sair</a>
</body>
</html>
