

<style>
    body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
    .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 900px; margin: auto; }
    h1, h2 { color: #333; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 5px; font-weight: bold; }
    input[type="text"], input[type="password"] { /* Removido 'input[type="email"], select' */
        width: calc(100% - 22px); padding: 10px; border: 1px solid #ddd; border-radius: 4px;
    }
    button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
    button:hover { background-color: #0056b3; }
    .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
    .message.success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
    .message.error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    .actions a, .actions button { margin-right: 5px; text-decoration: none; color: #007bff; background: none; border: none; padding: 0; cursor: pointer; font-size: inherit;}
    .actions a:hover, .actions button:hover { text-decoration: underline; }
</style>

<div class="container">
    <h1>Gerenciamento de Administradores</h1>

    <?php if (!empty($mensagem)): ?>
        <div class="message <?php echo htmlspecialchars($tipo_mensagem); ?>">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <h2><?php echo $admin_para_editar ? 'Editar Administrador' : 'Criar Novo Administrador'; ?></h2>
    <form action="<?= BASE_URL ?>admin/processar-admin-form" method="POST">
        <?php echo csrf_input(); ?>
        <?php if ($admin_para_editar): ?>
            <input type="hidden" name="action" value="editar_admin">
            <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_para_editar['id']); ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="criar_admin">
        <?php endif; ?>

        <div class="form-group">
            <label for="nome_usuario">Nome de Usuário:</label>
            <input type="text" id="nome_usuario" name="nome_usuario" value="<?php echo htmlspecialchars($admin_para_editar['nome_usuario'] ?? ''); ?>" required>
        </div>
        
        <?php if (!$admin_para_editar): ?>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
        <?php endif; ?>
        

        <button type="submit"><?php echo $admin_para_editar ? 'Salvar Alterações' : 'Criar Administrador'; ?></button>
        <?php if ($admin_para_editar): ?>
            <a href="<?= BASE_URL ?>admin/gerenciar-admins" style="margin-left: 10px;">Cancelar Edição</a>
        <?php endif; ?>
    </form>

    <?php if ($admin_para_editar): ?>
        <h2 style="margin-top: 30px;">Atualizar Senha de <?php echo htmlspecialchars($admin_para_editar['nome_usuario']); ?></h2>
        <form action="<?= BASE_URL ?>admin/processar-admin-form" method="POST">
            <?php echo csrf_input(); ?>
            <input type="hidden" name="action" value="atualizar_senha">
            <input type="hidden" name="admin_id_senha" value="<?php echo htmlspecialchars($admin_para_editar['id']); ?>">
            <div class="form-group">
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" id="nova_senha" name="nova_senha" required>
            </div>
            <div class="form-group">
                <label for="confirmar_nova_senha">Confirmar Nova Senha:</label>
                <input type="password" id="confirmar_nova_senha" name="confirmar_nova_senha" required>
            </div>
            <button type="submit">Atualizar Senha</button>
        </form>
    <?php endif; ?>

    <h2 style="margin-top: 30px;">Lista de Administradores</h2>
    <?php if (!empty($lista_admins)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_admins as $admin): ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin['id']); ?></td>
                    <td><?php echo htmlspecialchars($admin['nome_usuario']); ?></td>
                    <td class="actions">
                        <a href="<?= BASE_URL ?>admin/gerenciar-admins?action=editar&id=<?php echo htmlspecialchars($admin['id']); ?>">Editar</a>
                        <form action="<?= BASE_URL ?>admin/processar-admin-form" method="POST" style="display:inline;">
                            <?php echo csrf_input(); ?>
                            <input type="hidden" name="action" value="excluir_admin">
                            <input type="hidden" name="admin_id_excluir" value="<?php echo htmlspecialchars($admin['id']); ?>">
                            <button type="submit" onclick="return confirm('Tem certeza que deseja excluir o administrador <?php echo htmlspecialchars($admin['nome_usuario']); ?>? Esta ação é irreversível.');">Excluir</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum administrador encontrado.</p>
    <?php endif; ?>
</div>
