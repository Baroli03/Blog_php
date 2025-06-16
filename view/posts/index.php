<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($error_message) && $error_message) { // Correção: usar $error_message diretamente
    echo '<div class="alert alert-danger">' . htmlspecialchars($error_message) . '</div>';
    unset($_SESSION['error_message']);
}
?>
<div class="lista-posts">
    <?php
    if (!empty($posts)) {
        foreach ($posts as $post) {
        ?>
            <div class="post-item">
            <h2>
                <a class="titulo" href="<?= BASE_URL ?>id/<?php echo htmlspecialchars($post->id); ?>"> <?php echo htmlspecialchars($post->titulo); ?></a>
            </h2>

            <span class="publicado">Publicado em: <?php echo $post->data_criacao ?></span>
            <hr>
            <div class="post">
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

                        echo substr($conteudo_final, 0, 200); 
                        if (mb_strlen($post->conteudo) > 200) { 
                            echo '...'; 
                        }
                    }
                ?>
            </div>
            <div class="comentario_autor">
                <?php
                    if (!empty($post->comentario_autor)){
                        echo htmlspecialchars($post->comentario_autor);
                    }
                ?>
            </div>
            <p>
                <a href="<?= BASE_URL ?>id/<?php echo htmlspecialchars($post->id); ?>">
                    Leia Mais
                </a>
            </p>

            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['admin_id'])) : ?>
                <div class="admin-actions">
                    <a href="<?= BASE_URL ?>posts/edit/<?php echo htmlspecialchars($post->id); ?>" class="btn-edit">Editar</a>
                    <a href="<?= BASE_URL ?>posts/delete/<?php echo htmlspecialchars($post->id); ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este post?');">Excluir</a>
                </div>
            <?php endif; ?>

            </div>
    <?php }
} else {
        echo '<p>Nenhum post encontrado no momento.</p>';
    }?>
</div>

<?php
    if(isset($totalPaginas) && $totalPaginas > 1) :
?>

<div class="paginacao">
    <?php
        if(isset($paginaAtual) && $paginaAtual > 1) :
    ?>
        <a href="<?= BASE_URL ?>posts/index/<?php echo ($paginaAtual - 1); ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php
        for ($i = 1; $i <= $totalPaginas; $i++) :
            ?>
                <a href="<?= BASE_URL ?>posts/index/<?php echo $i; ?>"
                class="<?php echo (isset($paginaAtual) && $i == $paginaAtual) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
    <?php endfor; ?>


<?php
    if (isset($paginaAtual) && $paginaAtual < $totalPaginas) :
?>

<a href="<?= BASE_URL ?>posts/index/<?php echo ($paginaAtual + 1); ?>">Próxima &raquo;</a>
<?php endif; ?>
</div> <?php endif; ?>