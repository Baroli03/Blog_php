<?php
require_once __DIR__ . '/../config/banco.php';

class User
{
    public static function getByUsername($nome_usuario)
{
    $conn = Banco::getConn();
    $stmt = $conn->prepare("SELECT * FROM admin WHERE nome_usuario = ?");
    $stmt->bind_param("s", $nome_usuario);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
}
