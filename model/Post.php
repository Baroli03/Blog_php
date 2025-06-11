<?php

require_once __DIR__ . "/../config/Banco.php";

class Post {

    
    public static function listarPosts($posts_por_pagina, $OFFSET ){

         $sql = "SELECT id, titulo, conteudo, data_criacao, comentario_autor
                  FROM posts
                  ORDER BY data_criacao DESC
                  LIMIT :posts_por_pagina OFFSET :offset";    
    $result = Banco::getConn()->query($sql);
    return $result

    }
   

    public static function contarTodosPosts() {
        $sql = "SELECT COUNT(*) as total_posts FROM posts";
        $result = Banco::getConn()->query($sql);
        $row = $result->fetch_object();
        return $row->total_posts;
        
    }
}

?>