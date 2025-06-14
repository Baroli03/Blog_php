<?php
require_once __DIR__ . '/../config/banco.php';

class Comentario {
    public static function buscarPorPost($postId) {
        global $pdo;
        $sql = "SELECT * FROM comentarios WHERE post_id = ? ORDER BY data_criacao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function salvar($postId, $usuario, $comentario) {
        global $pdo;
        $sql = "INSERT INTO comentarios (post_id, usuario, comentario) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$postId, $usuario, $comentario]);
    }
}
