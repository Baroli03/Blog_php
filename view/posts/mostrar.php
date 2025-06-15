<div class="post-unico">
 <?php if ($post) :?>
    <h1><?php echo htmlspecialchars($post->titulo); ?></h1>
    <span class="publicado">Publicado em: <?php echo $post->data_criacao; ?></span>
    <div class="conteudo_post">
        <?php  
        $conteudo_processado = htmlspecialchars($post->conteudo); 

        if (str_starts_with($conteudo_processado, 'data:image/')) {
            echo '<img src="' . $conteudo_processado . '" alt="Imagem do post" style="max-width:100%; height:auto;">';
        } 
        else if (filter_var($post->conteudo, FILTER_VALIDATE_URL)) {
            echo '<img src="' . $conteudo_processado . '" alt="Imagem do post" style="max-width:100%; height:auto;">';
        } 
        else {
            $conteudo_final = preg_replace(
                '~(http|https|ftp)://([a-zA-Z0-9\-\.]+)\.([a-zA-Z]{2,3})([a-zA-Z0-9\-\.,\?\'\/\\\+&%\$#_=]*)~i',
                '<a href="$0" target="_blank">$0</a>', 
                nl2br($conteudo_processado) 
            );

            echo $conteudo_final; 
        }
        ?>
    </div>
    <?php if (!empty($post->comentario_autor)) : ?>
            <div class="comentario_autor">
                <p>Comentário do Autor: <em><?php echo htmlspecialchars($post->comentario_autor); ?></em></p>
            </div>
    <?php endif; ?>

    <hr> <div class="comentario-form-container">
        <h3>Deixe seu Comentário</h3>
        <?php 
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="<?= BASE_URL ?>posts/addComentario/<?php echo htmlspecialchars($post->id); ?>" method="POST">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            <div class="form-group">
                <label for="email">Email (Opcional):</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="conteudo">Comentário:</label>
                <textarea id="conteudo" name="conteudo" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Comentário</button>
        </form>
    </div>
    
    <hr> <div class="lista-comentarios">
        <h3>Comentários (<?php echo count($comentarios); ?>)</h3>
        <?php if (!empty($comentarios)) : ?>
            <?php foreach ($comentarios as $comentario) : ?>
                <div class="comentario-item">
                    <h4><?php echo htmlspecialchars($comentario->nome); ?> em <?php echo $comentario->data_comentario; ?></h4>
                    <p><?php echo nl2br(htmlspecialchars($comentario->conteudo)); ?></p>
                    <?php if (isset($is_admin_logged_in) && $is_admin_logged_in && !$comentario->aprovado) : ?>
                        <div class="admin-comentario-actions">
                            <form action="<?= BASE_URL ?>posts/aprovarComentario/<?php echo htmlspecialchars($comentario->id); ?>" method="POST" style="display:inline;">
                                <button type="submit" class="btn btn-success btn-sm">Aprovar</button>
                            </form>
                            </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Nenhum comentário ainda. Seja o primeiro a comentar!</p>
        <?php endif; ?>
    </div>


    <p>
        <a href="<?= BASE_URL ?>posts/index">Voltar para a lista de posts</a>
    </p>
    <?php else : ?>
        <p>Post não encontrado.</p> 
    <?php endif; ?>
</div>