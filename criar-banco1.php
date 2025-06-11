<?php

$host     = 'localhost:3306';
$user     = 'root';
$senha = '';
$dbNome   = 'blog_db';



$conn = new mysqli($host, $user, $senha);
if ($conn->connect_error) {
    die('Falha na conexão: ' . $conn->connect_error);
}

echo "Conectado ao MySQL com sucesso.<br>";

$sql_drop_db = "DROP DATABASE IF EXISTS `$dbNome`";

if ($conn->query($sql_drop_db) === TRUE) {
    echo "Banco de dados '$dbNome' removido (ou já inexistente) com sucesso.<br>";
} else {
    echo "Erro ao remover o banco de dados '$dbNome': " . $conn->error . "<br>";
}


$sql = "CREATE DATABASE IF NOT EXISTS `$dbNome` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Banco de dados '$dbNome' criado (ou já existente).<br>";
} else {
    echo "Erro ao criar o banco de dados: " . $conn->error . "<br>";
}


$conn->select_db($dbNome);

$conn->set_charset('utf8mb4');


echo "Selecionado o banco de dados '$dbNome'.<br>";


$tables = [
    'admin' => "
        CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome_usuario VARCHAR(50) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL
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
            -- NOVO CAMPO: comentario_autor para identificar o autor do comentário (se houver)
            comentario_autor VARCHAR(255) NULL,
            email VARCHAR(255) NULL, -- Opcional, pode ser nulo
            conteudo TEXT NOT NULL,
            data_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
            aprovado BOOLEAN DEFAULT FALSE, -- Campo para moderação (falso por padrão)
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
];


foreach ($tables as $nome => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Tabela '$nome' criada com sucesso.<br>";
    } else {
        echo "Erro ao criar tabela '$nome': " . $conn->error . "<br>";
    }
}


$adminSenha = '123';
$hashAdmin = password_hash($adminSenha, PASSWORD_DEFAULT);

$inserindo = [
    "INSERT IGNORE INTO admin (nome_usuario, senha) VALUES ('admin', '$hashAdmin')",

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
    if ($conn->query($sql) === TRUE) {
        echo "Inserção executada: " . htmlspecialchars($sql) . "<br>";
    } else {
        echo "Erro na inserção: " . $conn->error . "<br>";
    }
}



$conn->close();

echo "<br>Script finalizado.";
?>
