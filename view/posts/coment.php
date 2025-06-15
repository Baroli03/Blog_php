<?php
require_once __DIR__ . '/../config/banco.php';

class Comment {
    public static function getByPostId($post_id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM comentarios WHERE post_id = ? ORDER BY created_at DESC");
        $stmt->execute([$post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addComment($post_id, $author, $content) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO comentarios (post_id, author, content, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$post_id, $author, $content]);
    }
}
?>
