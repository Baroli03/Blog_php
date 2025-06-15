<?php
require_once __DIR__ . '/../config/banco.php';

class Comentario
{
    public static function adicionarComentario($post_id, $nome, $email, $conteudo)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("INSERT INTO comentarios (post_id, nome, email, conteudo, aprovado) VALUES (:post_id, :nome, :email, :conteudo, FALSE)");

            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR); 
            $stmt->bindParam(':conteudo', $conteudo, PDO::PARAM_STR);

            return $stmt->execute(); 
        } catch (PDOException $e) {
            error_log("Erro ao adicionar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function listarComentariosPorPost($post_id, $apenas_aprovados = true)
    {
        try {
            $conn = Banco::getConn();
            $sql = "SELECT id, post_id, nome, email, conteudo, data_comentario, aprovado FROM comentarios WHERE post_id = :post_id";

            if ($apenas_aprovados) {
                $sql .= " AND aprovado = TRUE";
            }
            $sql .= " ORDER BY data_comentario ASC";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ); 
        } catch (PDOException $e) {
            error_log("Erro ao listar comentários: " . $e->getMessage());
            return []; 
        }
    }

    public static function aprovarComentario($comentario_id)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("UPDATE comentarios SET aprovado = TRUE WHERE id = :id");
            $stmt->bindParam(':id', $comentario_id, PDO::PARAM_INT);

            return $stmt->execute(); 
        } catch (PDOException $e) {
            error_log("Erro ao aprovar comentário: " . $e->getMessage());
            return false;
        }
    }

    public static function deletarComentario($comentario_id)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("DELETE FROM comentarios WHERE id = :id");
            $stmt->bindParam(':id', $comentario_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar comentário: " . $e->getMessage());
            return false;
        }
    }
}