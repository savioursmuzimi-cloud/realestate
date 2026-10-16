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
    // Detect whether the project is installed at the domain root (shared hosting)
    // or inside a local subfolder such as /HomeLink-KE (XAMPP).
    $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
    if ($dir === '.' || $dir === '/') return '';
    $knownAppDirs = ['admin', 'properties', 'pages', 'forms', 'auth', 'includes', 'config', 'assets', 'uploads'];
    $last = basename($dir);
    if (in_array($last, $knownAppDirs, true)) {
        $parent = rtrim(str_replace('\\', '/', dirname($dir)), '/');
        return ($parent === '.' || $parent === '/') ? '' : $parent;
    }
    return $dir;
}
function redirectTo(string $path): never { header('Location: ' . appBaseUrl() . '/' . ltrim($path, '/')); exit; }
function isAdminLoggedIn(): bool { return !empty($_SESSION['admin_id']); }
function checkAdminLoggedIn(): void { if (!isAdminLoggedIn()) redirectTo('admin/login.php'); }
function isSuperAdmin(): bool { return isAdminLoggedIn() && (($_SESSION['admin_role'] ?? '') === 'super_admin'); }
function requireSuperAdmin(): void { checkAdminLoggedIn(); if (!isSuperAdmin()) { http_response_code(403); exit('Access denied. Super administrator privileges are required.'); } }
function logoutAdmin(): never {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
    redirectTo('admin/login.php');
}
