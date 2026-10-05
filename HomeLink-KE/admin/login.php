<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isAdminLoggedIn()) {
    redirectTo('admin/dashboard.php');
}

$conn = getDBConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();    
    $username = post('username');
    $password = (string)($_POST['password'] ?? '');
    
    $stmt = $conn->prepare('SELECT id,username,password,role,active FROM admins WHERE username=? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $a = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($a && $a['active'] && password_verify($password, $a['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$a['id'];
        $_SESSION['admin_username'] = $a['username'];
        $_SESSION['admin_role'] = $a['role'];
        
        logAction($conn, 'Admin login');
        redirectTo('admin/dashboard.php');
    }
    
    $error = 'Invalid username or password.';
}

$pageTitle = 'Admin Login';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Login | HomeLink-KE</title>
    <link rel="stylesheet" href="<?= appBaseUrl() ?>/assets/folder/style.css">
</head>
<body>
    <div class="login-screen">
        <div class="login-card">
            <div class="brand-mark">
                <i class="fa-solid fa-house"></i>
            </div>
            <div class="kicker" style="margin-top:18px">PRIVATE ADMIN AREA</div>
            <h1>HomeLink-KE Admin</h1>
            <p class="muted">Manage listings, requests, settings and BnB bookings.</p>
            
            <?php if ($error): ?>
                <div class="alert error"><?= e($error) ?></div>
            <?php endif; ?>
            
            <form method="post">
                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                
                <div class="field">
                    <label>Username</label>
                    <input name="username" value="<?= e($_POST['username'] ?? '') ?>" required autofocus>
                </div>
                
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button class="btn btn-primary" style="width:100%">Sign in</button>
            </form>
            
            <p class="muted" style="font-size:12px;margin-top:18px">
               
            </p>
            <a href="<?= appBaseUrl() ?>/index.php" style="font-size:13px">← Back to website</a>
        </div>
    </div>
</body>
</html>