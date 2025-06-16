<?php


class ConexaoPDO {
    private static $pdo_instance;
    private static $host = 'localhost';
    private static $port = '3306';
    private static $user = 'root';
    private static $senha = '';
    private static $dbNome = 'blog_db'; 


    public static function getConexao() {
        if (!self::$pdo_instance) {
            $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbNome . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro de SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays associativos por padrão
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Desabilita a emulação para prepared statements reais (mais seguro)
            ];

            try {
                self::$pdo_instance = new PDO($dsn, self::$user, self::$senha, $options);
            } catch (PDOException $e) {
                error_log("Erro de conexão PDO: " . $e->getMessage()); // Loga o erro em vez de mostrar na tela em produção
                die('Falha na conexão com o banco de dados. Por favor, tente novamente mais tarde.');
            }
        }
        return self::$pdo_instance;
    }

    
    public static function initializeDatabase() {
        try {
            $conn_no_db = new PDO("mysql:host=" . self::$host . ";port=" . self::$port . ";charset=utf8mb4", self::$user, self::$senha);
            $conn_no_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_drop_db = "DROP DATABASE IF EXISTS `" . self::$dbNome . "`";
            $conn_no_db->exec($sql_drop_db);
            echo "Banco de dados '" . self::$dbNome . "' removido (ou já inexistente) com sucesso.<br>";

            $sql_create_db = "CREATE DATABASE IF NOT EXISTS `" . self::$dbNome . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            $conn_no_db->exec($sql_create_db);
            echo "Banco de dados '" . self::$dbNome . "' criado (ou já existente).<br>";

            $pdo = self::getConexao();

            $tables = [
                'admin' => "
                    CREATE TABLE IF NOT EXISTS admin (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nome_usuario VARCHAR(50) NOT NULL UNIQUE,
                        senha_hash VARCHAR(255) NOT NULL -- Campo para o hash da senha
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

                'posts' => "
                    CREATE TABLE IF NOT EXISTS posts (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        admin_id INT NOT NULL,
                        titulo VARCHAR(255) NOT NULL,
                        conteudo TEXT NOT NULL,
                        comentario_autor TEXT NOT NULL,
                        data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

                'comentarios' => "
                    CREATE TABLE IF NOT EXISTS comentarios (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        post_id INT NOT NULL,
                        nome VARCHAR(255) NOT NULL,
                        comentario_autor VARCHAR(255) NULL,
                        email VARCHAR(255) NULL,
                        conteudo TEXT NOT NULL,
                        data_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
                        aprovado BOOLEAN DEFAULT FALSE,
                        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
            ];

            foreach ($tables as $nome => $sql) {
                $pdo->exec($sql);
                echo "Tabela '$nome' criada com sucesso.<br>";
            }

            $adminSenha = '123';
            $hashAdmin = password_hash($adminSenha, PASSWORD_BCRYPT); 

            $inserindo = [
                "INSERT IGNORE INTO admin (nome_usuario, senha_hash) VALUES ('admin', '$hashAdmin')",

                "INSERT IGNORE INTO posts (admin_id, titulo, conteudo, comentario_autor) VALUES (1, 'Primeiro Post do Blog', 'Bem-vindo ao meu novo blog! Este é o primeiro post de muitos.', 'Isso é uma atividade de php')",
                "INSERT IGNORE INTO posts (admin_id, titulo, conteudo, comentario_autor) VALUES (1, 'Sobre Desenvolvimento Web', 'Neste post, exploraremos as últimas tendências em desenvolvimento web e suas tecnologias.', 'mentira, isso foi algo aleatório escrito somente para encher linguiça')",
                "INSERT IGNORE INTO posts (admin_id, titulo, conteudo, comentario_autor) VALUES (1, 'Dicas para Programadores Iniciantes', 'Compartilhando algumas dicas valiosas para quem está começando no mundo da programação.', 'estou chorando por dentro, fiquem atentos para mais dicas')",
                "INSERT IGNORE INTO posts (admin_id, titulo, conteudo, comentario_autor) VALUES (1, 'A Importância do Design Responsivo', 'Discutindo por que o design responsivo é crucial para a experiência do usuário hoje em dia.', 'unica coisa responsiva em mim é a ansiedade que toda vez q tem prova ela vem')",

                "INSERT INTO comentarios (post_id, nome, email, conteudo, aprovado) VALUES (1, 'Visitante Curioso', 'visitante@example.com', 'Ótimo primeiro post! Mal posso esperar pelos próximos.', TRUE)",
                "INSERT INTO comentarios (post_id, nome, email, conteudo, aprovado) VALUES (1, 'Leitor Assíduo', NULL, 'Concordo, o blog promete!', TRUE)",
                "INSERT INTO comentarios (post_id, nome, email, conteudo, aprovado) VALUES (2, 'Desenvolvedor Junior', 'dev.junior@example.com', 'Interessante as tendências! Qual framework vocês mais recomendam?', FALSE)",
                "INSERT INTO comentarios (post_id, nome, email, conteudo, aprovado) VALUES (3, 'Estudante Code', 'estudante@example.com', 'As dicas foram muito úteis para mim, obrigado!', TRUE)"
            ];

            foreach ($inserindo as $sql) {
                $pdo->exec($sql);
                echo "Inserção executada: " . htmlspecialchars($sql) . "<br>";
            }

            echo "<br>Script de inicialização do banco de dados finalizado com PDO.";

        } catch (PDOException $e) {
            error_log("Erro na inicialização do banco de dados: " . $e->getMessage());
            die('Erro ao inicializar o banco de dados: ' . $e->getMessage());
        }
    }
}

// DEPOIS DE EXECUTAR, COMENTE-A NOVAMENTE para evitar que o banco seja recriado a cada requisição.
// ConexaoPDO::initializeDatabase(); 

?>
