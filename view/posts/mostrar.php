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
<<<<<<< HEAD
</div>
=======
</div>

<hr>

<h3>Comentários</h3>
<?php if (!empty($comentarios)): ?>
    <ul>
        <?php foreach ($comentarios as $comentario): ?>
            <li style="margin-bottom: 10px;">
                <strong><?= htmlspecialchars($comentario['author']) ?></strong> em <?= $comentario['created_at'] ?><br>
                <?= nl2br(htmlspecialchars($comentario['content'])) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Seja o primeiro a comentar!</p>
<?php endif; ?>

<hr>

<h3>Adicionar Comentário</h3>
<form action="/posts/addComment/<?= $post['id'] ?>" method="POST">
    <label for="author">Nome:</label><br>
    <input type="text" name="author" required><br><br>

    <label for="content">Comentário:</label><br>
    <textarea name="content" required rows="4" cols="50"></textarea><br><br>

    <button type="submit">Enviar Comentário</button>
</form>

<?php if (!empty($_SESSION['msg'])): ?>
    <p style="color: green;"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></p>
<?php endif; ?>
>>>>>>> baecbd1 (up)
