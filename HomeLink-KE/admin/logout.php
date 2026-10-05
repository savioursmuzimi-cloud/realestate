<?php 
require_once __DIR__ . '/../includes/auth.php'; 

if (isAdminLoggedIn()) {
    try {
        require_once __DIR__ . '/../config/database.php';
        $c = getDBConnection();
        logAction($c, 'Admin logout');
    } catch (Throwable $e) {
    }
} 

logoutAdmin();