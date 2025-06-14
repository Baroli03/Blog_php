<?php 
    abstract class Banco {
        private static $conn;

        public static function getConn()
        {
            if (!isset(self::$conn)) {
                self::$conn = new mysqli("localhost", "root", "", "blog_db");


                if (self::$conn->connect_error) {
                    die("Erro ao conectar ao banco: " . self::$conn->connect_error);
                }
            }

            return self::$conn;
        }
    }

?>