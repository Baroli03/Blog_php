<?php

require_once __DIR__ . "/../config/Banco.php";

class Post {
    public static function listarPosts($posts_por_pagina, $OFFSET ){
        try{
             $sql = "SELECT id, titulo, conteudo, comentario_autor, data_criacao
                  FROM posts
                  ORDER BY data_criacao DESC
                  LIMIT :posts_por_pagina OFFSET :offset";    
    $result = Banco::getConn()->prepare($sql);
    $result->bindParam(':posts_por_pagina', $posts_por_pagina, PDO::PARAM_INT);
    $result->bindParam(':offset', $OFFSET, PDO::PARAM_INT);
    $result->execute();
    return $result
    }catch (PDOException $e) {
        error_log("Erro ao listar posts: " . $e->getMessage());
        return false;
    }
}

        
    public static function contarTodosPosts() {
        $sql = "SELECT COUNT(*) as total_posts FROM posts";
        $result = Banco::getConn()->query($sql);
        $row = $result->fetch_object();
        return $row->total_posts;
        
    }


    public static function pegarPostId($id) {
        $sql = "SELECT id, titulo, conteudo, data_criacao, comentario_autor
                FROM posts
                WHERE id = " . (int)$id . " LIMIT 1";
        $result = Banco::getConn()->query($sql);
        if ($result && $result->num_rows > 0) { 
            return $result->fetch_object();
        }
        return null;
    }


    public static function deletarPost($id) {
        $sql = "DELETE FROM posts WHERE id = :id";

        try {
            $result = Banco::getConn()->prepare($sql);
            $result->bindParam(':id', $id, PDO::PARAM_INT);
            $sucesso = $result->execute();
            return $sucesso;
    
        }catch (PDOException $e) {
        error_log("Erro ao deletar post: " . $e->getMessage());
        return false;
        }
}
}
?>