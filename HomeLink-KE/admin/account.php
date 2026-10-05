<?php 
require_once __DIR__ . '/../includes/admin.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $current = (string)($_POST['current_password'] ?? '');
    $new = (string)($_POST['new_password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    
    $stmt = $conn->prepare('SELECT password FROM admins WHERE id=?');
    $stmt->bind_param('i', $_SESSION['admin_id']);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$row || !password_verify($current, $row['password'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('UPDATE admins SET password=? WHERE id=?');
        $stmt->bind_param('si', $hash, $_SESSION['admin_id']);
        $stmt->execute();
        $stmt->close();
        
        logAction($conn, 'Changed own admin password');
        flash('success', 'Your password has been changed.');
        redirectTo('admin/account.php');
    }
}

adminPageStart('My password', 'account');
?>

<?php if ($error): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>

<div class="admin-card" style="max-width:650px">
    <h2>Change your password</h2>
    <p class="muted">Use this if you want to replace the default password or update your existing password.</p>
    
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        
        <div class="field">
            <label>Current password</label>
            <input type="password" name="current_password" required>
        </div>
        
        <div class="field">
            <label>New password</label>
            <input type="password" name="new_password" minlength="8" required>
        </div>
        
        <div class="field">
            <label>Confirm new password</label>
            <input type="password" name="confirm_password" minlength="8" required>
        </div>
        
        <button class="btn btn-primary">Change password</button>
    </form>
</div>

<?php adminPageEnd(); ?>