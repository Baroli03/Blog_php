<div class="login-box">
    <h2>Login do Administrador</h2>
    <?php if (!empty($erro)) : ?>
        <div class="erro-login"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= BASE_URL ?>admin/login">
        <?php echo csrf_input(); ?>
        <label for="nome_usuario">Usuário:</label>
        <input type="text" id="nome_usuario" name="nome_usuario" required />

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required />

        <button type="submit">Entrar</button>
    </form>
</div>