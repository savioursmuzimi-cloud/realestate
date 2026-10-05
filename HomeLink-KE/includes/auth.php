<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('homelink_session');
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}

function appBaseUrl(): string { 
    return '/HomeLink-KE'; 
}

function redirectTo(string $path): never { 
    header('Location: ' . appBaseUrl() . '/' . ltrim($path, '/')); 
    exit; 
}

function isAdminLoggedIn(): bool { 
    return !empty($_SESSION['admin_id']); 
}

function checkAdminLoggedIn(): void { 
    if (!isAdminLoggedIn()) {
        redirectTo('admin/login.php'); 
    }
}

function isSuperAdmin(): bool { 
    return isAdminLoggedIn() && (($_SESSION['admin_role'] ?? '') === 'super_admin'); 
}

function requireSuperAdmin(): void { 
    checkAdminLoggedIn(); 
    if (!isSuperAdmin()) { 
        http_response_code(403); 
        exit('Access denied. Super administrator privileges are required.'); 
    } 
}

function logoutAdmin(): never {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    redirectTo('admin/login.php');
}