<?php
require_once __DIR__ . '/../model/User.php';

class AdminController {

   
    private static function checkAdminLogin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_id'])) {
            $_SESSION['error_message'] = 'Acesso restrito. Faça login como administrador.';
            header("Location: " . BASE_URL . "admin/login"); 
            exit();
        }
      
    }

    public static function painel() { 
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_id'])) {
            $_SESSION['error_message'] = 'Acesso restrito. Faça login como administrador.';
            header("Location: " . BASE_URL . "admin/login"); 
            exit();
        }

        $data_para_view = [
            'titulo_pagina' => 'Painel Administrativo',
        ];
        extract($data_para_view); 

        include __DIR__ . '/../view/partes/header.php';
        include __DIR__ . '/../view/admin/painel.php'; 
        include __DIR__ . '/../view/partes/footer.php';
    }

   
    public static function gerenciarAdmins() {
        self::checkAdminLogin(); 

        $admin_para_editar = null;
        $mensagem = $_SESSION['message'] ?? '';
        $tipo_mensagem = $_SESSION['message_type'] ?? '';

        unset($_SESSION['message']);
        unset($_SESSION['message_type']);

        if (isset($_GET['action']) && $_GET['action'] === 'editar' && isset($_GET['id'])) {
            $admin_id = (int)$_GET['id'];
            $admin_para_editar = User::getById($admin_id);
            if (!$admin_para_editar) {
                $mensagem = 'Administrador não encontrado para edição.';
                $tipo_mensagem = 'error';
            }
        }

        $lista_admins = User::getAllUsers(); 

        $data_para_view = [
            'titulo_pagina'     => 'Gerenciamento de Administradores',
            'admin_para_editar' => $admin_para_editar,
            'lista_admins'      => $lista_admins,
            'mensagem'          => $mensagem,
            'tipo_mensagem'     => $tipo_mensagem,
        ];
        extract($data_para_view);

        include __DIR__ . '/../view/partes/header.php';
        include __DIR__ . '/../view/admin/admin_gerenciamento.php'; 
        include __DIR__ . '/../view/partes/footer.php';
    }

   
    public static function processarAdminForm() {
        self::checkAdminLogin(); 

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            validar_csrf_token(); 
            $resultado = ['success' => false, 'message' => 'Ação inválida.'];

            switch ($_POST['action']) {
                case 'criar_admin':
                    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
                    $senha_pura = $_POST['senha'] ?? '';
                    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

                    if (empty($nome_usuario) || empty($senha_pura) || empty($confirmar_senha)) {
                        $resultado = ['success' => false, 'message' => 'Todos os campos são obrigatórios.'];
                    } elseif ($senha_pura !== $confirmar_senha) {
                        $resultado = ['success' => false, 'message' => 'As senhas não coincidem.'];
                    } elseif (strlen($senha_pura) < 8) {
                        $resultado = ['success' => false, 'message' => 'A senha deve ter pelo menos 8 caracteres.'];
                    } elseif (User::getByUsername($nome_usuario)) { 
                        $resultado = ['success' => false, 'message' => 'Nome de usuário já existe.'];
                    } else {
                        $senha_hash = password_hash($senha_pura, PASSWORD_BCRYPT);
                        if ($senha_hash === false) {
                            $resultado = ['success' => false, 'message' => 'Erro ao processar a senha.'];
                        } else {
                            $id = User::create($nome_usuario, $senha_hash); 
                            if ($id) {
                                $resultado = ['success' => true, 'message' => 'Administrador criado com sucesso!'];
                            } else {
                                $resultado = ['success' => false, 'message' => 'Erro ao criar administrador.'];
                            }
                        }
                    }
                    break;

                case 'editar_admin':
                    $id = (int)($_POST['admin_id'] ?? 0);
                    $nome_usuario = trim($_POST['nome_usuario'] ?? '');
                    
                    if ($id === 0) {
                        $resultado = ['success' => false, 'message' => 'ID do administrador inválido para edição.'];
                    } elseif (empty($nome_usuario)) { 
                        $resultado = ['success' => false, 'message' => 'Nome de usuário é obrigatório.'];
                    } else {
                        $adminExistente = User::getByUsername($nome_usuario);
                        if ($adminExistente && $adminExistente['id'] != $id) {
                            $resultado = ['success' => false, 'message' => 'O nome de usuário já está em uso por outro administrador.'];
                        } else {
                            $success = User::update($id, $nome_usuario); 
                            if ($success) {
                                $resultado = ['success' => true, 'message' => 'Administrador editado com sucesso!'];
                            } else {
                                $resultado = ['success' => false, 'message' => 'Erro ao editar administrador.'];
                            }
                        }
                    }
                    break;

                case 'atualizar_senha':
                    $id = (int)($_POST['admin_id_senha'] ?? 0);
                    $nova_senha_pura = $_POST['nova_senha'] ?? '';
                    $confirmar_nova_senha = $_POST['confirmar_nova_senha'] ?? '';

                    if ($id === 0) {
                        $resultado = ['success' => false, 'message' => 'ID do administrador inválido para atualização de senha.'];
                    } elseif (empty($nova_senha_pura) || empty($confirmar_nova_senha)) {
                        $resultado = ['success' => false, 'message' => 'Todos os campos de senha são obrigatórios.'];
                    } elseif ($nova_senha_pura !== $confirmar_nova_senha) {
                        $resultado = ['success' => false, 'message' => 'As novas senhas não coincidem.'];
                    } elseif (strlen($nova_senha_pura) < 8) {
                        $resultado = ['success' => false, 'message' => 'A nova senha deve ter pelo menos 8 caracteres.'];
                    } else {
                        $nova_senha_hash = password_hash($nova_senha_pura, PASSWORD_BCRYPT);
                        if ($nova_senha_hash === false) {
                            $resultado = ['success' => false, 'message' => 'Erro ao processar a nova senha.'];
                        } else {
                            $success = User::updatePassword($id, $nova_senha_hash);
                            if ($success) {
                                $resultado = ['success' => true, 'message' => 'Senha do administrador atualizada com sucesso!'];
                            } else {
                                $resultado = ['success' => false, 'message' => 'Erro ao atualizar senha do administrador.'];
                            }
                        }
                    }
                    break;

                case 'excluir_admin':
                    $id = (int)($_POST['admin_id_excluir'] ?? 0);
                    
                    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $id) {
                        $resultado = ['success' => false, 'message' => 'Você não pode excluir sua própria conta de administrador.'];
                    } elseif ($id === 0) {
                        $resultado = ['success' => false, 'message' => 'ID do administrador inválido para exclusão.'];
                    } else {
                        $success = User::delete($id);
                        if ($success) {
                            $resultado = ['success' => true, 'message' => 'Administrador excluído com sucesso.'];
                        } else {
                            $resultado = ['success' => false, 'message' => 'Erro ao excluir administrador.'];
                        }
                    }
                    break;
            }

            $_SESSION['message'] = $resultado['message'];
            $_SESSION['message_type'] = $resultado['success'] ? 'success' : 'error';

            header("Location: " . BASE_URL . "admin/gerenciar-admins");
            exit;
        } else {
            header("Location: " . BASE_URL . "admin/gerenciar-admins");
            exit;
        }
    }
}
