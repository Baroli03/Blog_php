<div class="form-container">
    <h1><?php echo htmlspecialchars($titulo_pagina ?? 'Criar Novo Post'); ?></h1>

    <?php 
    if (isset($success_message) && $success_message) {
        echo '<div class="alert alert-success">' . htmlspecialchars($success_message) . '</div>';
    }
    if (isset($error_message) && $error_message) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
    }
    ?>

    <form action="<?= BASE_URL ?>posts/criar" method="POST">
        <?php echo csrf_input(); ?>
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required 
                   value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>">
            </div>

        <div class="form-group">
            <label for="conteudo">Conteúdo:</label>
            <textarea id="conteudo" name="conteudo" rows="10" required><?php 
                   echo htmlspecialchars($_POST['conteudo'] ?? ''); 
            ?></textarea>
        </div>

        <div class="form-group">
            <label for="comentario_autor">Comentário do Autor (Opcional):</label>
            <textarea id="comentario_autor" name="comentario_autor" rows="3"><?php 
                   echo htmlspecialchars($_POST['comentario_autor'] ?? ''); 
            ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Post</button>
        <a href="<?= BASE_URL ?>posts/index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>