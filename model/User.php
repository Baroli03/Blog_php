<?php

require_once __DIR__ . '/../config/conexao_pdo.php';

class User
{

    private static function getPdo()
    {
        return ConexaoPDO::getConexao();
    }

   
    public static function getByUsername($nome_usuario)
    {
        try {
            $conn = self::getPdo();
            // Seleciona id, nome_usuario e senha_hash (necessário para o login)
            $stmt = $conn->prepare("SELECT id, nome_usuario, senha_hash FROM admin WHERE nome_usuario = :nome_usuario");
            $stmt->bindParam(':nome_usuario', $nome_usuario, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por username: " . $e->getMessage());
            return false;
        }
    }

    public static function getAllUsers(): array
    {
        try {
            $conn = self::getPdo();
            $stmt = $conn->prepare("SELECT id, nome_usuario FROM admin ORDER BY nome_usuario ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar administradores: " . $e->getMessage());
            return [];
        }
    }

    
    public static function getById(int $id)
    {
        try {
            $conn = self::getPdo();
            $stmt = $conn->prepare("SELECT id, nome_usuario FROM admin WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar administrador por ID: " . $e->getMessage());
            return false;
        }
    }

    public static function create(string $nome_usuario, string $senha_hash): int|false
    {
        $conn = self::getPdo();
        try {
            $stmt = $conn->prepare("INSERT INTO admin (nome_usuario, senha_hash) VALUES (:nome_usuario, :senha_hash)");
            $stmt->bindParam(':nome_usuario', $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':senha_hash', $senha_hash, PDO::PARAM_STR);
            $stmt->execute();
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar administrador: " . $e->getMessage());
            return false;
        }
    }


    public static function update(int $id, string $nome_usuario): bool
    {
        $conn = self::getPdo();
        try {
            // Atualiza apenas o nome_usuario
            $stmt = $conn->prepare("UPDATE admin SET nome_usuario = :nome_usuario WHERE id = :id");
            $stmt->bindParam(':nome_usuario', $nome_usuario, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar administrador: " . $e->getMessage());
            return false;
        }
    }


    public static function updatePassword(int $id, string $nova_senha_hash): bool
    {
        $conn = self::getPdo();
        try {
            $stmt = $conn->prepare("UPDATE admin SET senha_hash = :senha_hash WHERE id = :id");
            $stmt->bindParam(':senha_hash', $nova_senha_hash, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar senha: " . $e->getMessage());
            return false;
        }
    }


    public static function delete(int $id): bool
    {
        $conn = self::getPdo();
        try {
            $stmt = $conn->prepare("DELETE FROM admin WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao excluir administrador: " . $e->getMessage());
            return false;
        }
    }
}
