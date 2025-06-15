<?php
require_once __DIR__ . '/../model/User.php';
class AdminController {

   
    private static function checkAdminLogin() {
        
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_id'])) {
            $_SESSION['error_message'] = 'Acesso restrito. Faça login como administrador.';
            header("Location: /admin/login");
            exit();
        }
    }

    
    public static function painel() { 
        self::checkAdminLogin();

      
        $data_para_view = [
            'titulo_pagina' => 'Painel Administrativo',
        ];
        extract($data_para_view); 

        include __DIR__ . '/../view/partes/header.php';
        include __DIR__ . '/../view/admin/painel.php'; 
        include __DIR__ . '/../view/partes/footer.php';
    }


}
?>