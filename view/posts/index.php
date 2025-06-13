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

<?php 
    if(isset($totalPaginas) && $totalPaginas > 1) :
?>

<div class="paginacao">
    <?php
        if(isset($paginaAtual) && $paginaAtual > 1) :
    ?>  
        <a href="/posts/index/<?php echo ($paginaAtual - 1); ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php 

        for ($i = 1; $i <= $totalPaginas; $i++) : 
            ?>
                <a href="/posts/index/<?php echo $i; ?>" 
                class="<?php echo (isset($paginaAtual) && $i == $paginaAtual) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
    <?php endfor; ?>


<?php
    if (isset($paginaAtual) && $paginaAtual < $totalPaginas) : 
?>

<a href="/posts/index/<?php echo ($paginaAtual + 1); ?>">Próxima &raquo;</a>
<?php endif; ?>
</div> <?php endif; ?>

