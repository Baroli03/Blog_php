<div class="post-unico">
 <?php if ($post) :?>
    <h1><?php echo htmlspecialchars($post->titulo); ?></h1>
    <span class="publicado">Publicado em: <?php echo $post->data_criacao; ?></span>
    <div class="conteudo_post">
        <?php  
        $eLink = filter_var($post->conteudo, FILTER_VALIDATE_URL);
            if ($eLink) {
                echo '<img src="'. htmlspecialchars($post->conteudo) .'" alt="">';
            }else {
                    echo nl2br(htmlspecialchars($post->conteudo));
            }
            
        ?>
    </div>
    <?php if (!empty($post->comentario_autor)) : ?>
            <div class="comentario_autor">
                <p>Comentário do Autor: <em><?php echo htmlspecialchars($post->comentario_autor); ?></em></p>
            </div>
    <?php endif; ?>

    <p>
        <a href="/posts/index">Voltar para a lista de posts</a>
    </p>
    <?php else : ?>
</div>