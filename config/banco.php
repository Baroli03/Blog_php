<?php 
    abstract class Banco {
        private static $conn;

        public static function getConn()
        {
            if (!isset(self::$conn)) {
                try {
                    // Configuração PDO (ajuste se seu banco de dados, usuário ou senha forem diferentes)
                    $dsn = "mysql:host=localhost;dbname=blog_db;charset=utf8";
                    $user = "root";
                    $password = "";

                    // Cria uma nova instância PDO
                    self::$conn = new PDO($dsn, $user, $password);
                    
                    // Define o modo de erro para lançar exceções para facilitar a depuração
                    self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Define o modo de busca padrão para objetos
                    self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

                } catch (PDOException $e) {
                    // Em caso de erro na conexão, mata a aplicação e exibe a mensagem de erro
                    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
                }
            }

            return self::$conn;
        }
    }
?>