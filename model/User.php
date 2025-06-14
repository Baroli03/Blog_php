<?php
require_once __DIR__ . '/../config/banco.php';

class User
{
    public static function getByUsername($nome_usuario){
    try{
        {
            $conn = Banco::getConn();
            $stmt = $conn->prepare("SELECT * FROM admin WHERE = :nome_usuario");
            $stmt->bind_param(':nome_usuario', $nome_usuario, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        }
        catch (PDOException $e) {
            error_log("Erro ao buscar usuário: " . $e->getMessage());
            return false; 
        }
}
}