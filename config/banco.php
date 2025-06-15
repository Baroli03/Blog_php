<?php 
    abstract class Banco {
        private static $conn;

        public static function getConn()
        {
            if (!isset(self::$conn)) {
                try {
                    $dsn = "mysql:host=localhost;dbname=blog_db;charset=utf8";
                    $user = "root";
                    $password = "";

                    self::$conn = new PDO($dsn, $user, $password);
                    
                    self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

                } catch (PDOException $e) {
                    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
                }
            }

            return self::$conn;
        }
    }
?>