<?php
require_once __DIR__ . '/../config/banco.php';

class User
{
    public static function getByUsername($nome_usuario){
        try {
            $conn = Banco::getConn(); // Pega a conexão PDO

            // Prepara a query usando PDO
            $stmt = $conn->prepare("SELECT * FROM admin WHERE nome_usuario = :nome_usuario");
            
            // Binda o parâmetro (substitui :nome_usuario pelo valor)
            $stmt->bindParam(':nome_usuario', $nome_usuario, PDO::PARAM_STR);
            
            // Executa a query
            $stmt->execute();
            
            // Retorna o resultado como um array associativo (PDO::FETCH_ASSOC)
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Em caso de erro na query, loga o erro e retorna false
            error_log("Erro ao buscar usuário: " . $e->getMessage());
            return false; 
        }
    }
}