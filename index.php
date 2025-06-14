<?php
require_once __DIR__ . '/config/banco.php';
require_once __DIR__ . '/model/Comentario.php';

// Exemplo de post_id (ajuste conforme necessário)
$postId = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'])) {
    $usuario = trim($_POST['usuario']);
    $comentario = trim($_POST['comentario']);
    if (!empty($usuario) && !empty($comentario)) {
        Comentario::salvar($postId, $usuario, $comentario);
        header("Location: index.php");
        exit;
    }
}

$comentarios = Comentario::buscarPorPost($postId);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Comentários do Post</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f4f4f4; }
        .comentario { background: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        textarea, input { width: 100%%; padding: 8px; margin: 6px 0; }
        button { padding: 10px 20px; background: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        form { background: #fff; padding: 15px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <h2>Comentários do Post #<?= $postId ?></h2>

    <?php foreach ($comentarios as $c): ?>
        <div class="comentario">
            <strong><?= htmlspecialchars($c['usuario']) ?></strong> disse:
            <p><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
            <small><?= $c['data_criacao'] ?></small>
        </div>
    <?php endforeach; ?>

    <h3>Deixe um comentário:</h3>
    <form method="post">
        <input type="text" name="usuario" placeholder="Seu nome" required><br>
        <textarea name="comentario" placeholder="Seu comentário" required></textarea><br>
        <button type="submit">Enviar</button>
    </form>

</body>
</html>
