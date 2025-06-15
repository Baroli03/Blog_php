<?php
require_once __DIR__ . '/../config/banco.php';

class Post
{
    public static function listarPosts($limit, $offset)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("SELECT id, titulo, conteudo, data_criacao, comentario_autor FROM posts ORDER BY data_criacao DESC LIMIT :limit OFFSET :offset");
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt; 

        } catch (PDOException $e) {
            error_log("Erro ao listar posts: " . $e->getMessage());
            return false;
        }
    }

    public static function pegarPostId($id)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("SELECT id, titulo, conteudo, data_criacao, comentario_autor FROM posts WHERE id = :id");
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_OBJ); 

        } catch (PDOException $e) {
            error_log("Erro ao pegar post por ID: " . $e->getMessage());
            return false;
        }
    }

    public static function criarPost($admin_id, $titulo, $conteudo, $comentario_autor)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("INSERT INTO posts (admin_id, titulo, conteudo, comentario_autor) VALUES (:admin_id, :titulo, :conteudo, :comentario_autor)");
            
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':conteudo', $conteudo, PDO::PARAM_STR);
            $stmt->bindParam(':comentario_autor', $comentario_autor, PDO::PARAM_STR);
            
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao criar post: " . $e->getMessage());
            return false;
        }
    }

    public static function atualizarPost($id, $titulo, $conteudo, $comentario_autor)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("UPDATE posts SET titulo = :titulo, conteudo = :conteudo, comentario_autor = :comentario_autor WHERE id = :id");
            
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':conteudo', $conteudo, PDO::PARAM_STR);
            $stmt->bindParam(':comentario_autor', $comentario_autor, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao atualizar post: " . $e->getMessage());
            return false;
        }
    }

    public static function deletarPost($id)
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("DELETE FROM posts WHERE id = :id");
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("Erro ao deletar post: " . $e->getMessage());
            return false;
        }
    }

    public static function contarTodosPosts()
    {
        try {
            $conn = Banco::getConn();
            $stmt = $conn->query("SELECT COUNT(*) FROM posts");
            
            return $stmt->fetchColumn(); 

        } catch (PDOException $e) {
            error_log("Erro ao contar posts: " . $e->getMessage());
            return 0; 
        }
    }
}