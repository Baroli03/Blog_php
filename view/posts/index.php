<div class="lista-posts">
    <?php
    if (!empty($posts)) {
        foreach ($posts as $post) {
        ?>  
            <div class="post-item">
            <h2>
                <a href="/posts/id/<?php echo htmlspecialchars($post->id); ?>"> <?php echo htmlspecialchars($post->titulo); ?></a>
            </h2>

            <span class="publicado"><?php echo $post->data_criacao ?></span>
            <hr>
            <div class="post">
                <?php  
                    $eLink = filter_var($post->conteudo, FILTER_VALIDATE_URL);
                    if ($eLink) {
                        echo '<img src="'. htmlspecialchars($post->conteudo) .'" alt="">';
                    }else {
                         echo nl2br(htmlspecialchars(substr($post->conteudo, 0, 200)));
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
                <a href="/posts/id/<?php echo htmlspecialchars($post->id); ?>">
                    Leia Mais
                </a>
            </p>

            </div>

        
    <?php }
} else { 

        echo '<p>Nenhum post encontrado no momento.</p>';
    }?>

</div>