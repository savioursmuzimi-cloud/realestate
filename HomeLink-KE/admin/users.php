<?php 
require_once __DIR__ . '/../includes/admin.php';
requireSuperAdmin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $action = post('action');
    
    if ($action === 'add') {
        $u = post('username');
        $name = post('full_name');
        $role = post('role', 'admin');
        $pw = (string)($_POST['password'] ?? '');
        
        if ($u === '' || $pw === '') {
            $error = 'Username and password are required.';
        } else {
            $hash = password_hash($pw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO admins(username,password,full_name,role) VALUES(?,?,?,?)');
            $stmt->bind_param('ssss', $u, $hash, $name, $role);
            
            if ($stmt->execute()) {
                logAction($conn, "Created admin $u");
                flash('success', 'Admin account created.');
                redirectTo('admin/users.php');
            } else {
                $error = 'Username may already exist.';
            }
            
            $stmt->close();
        }
    } elseif ($action === 'password') {
        $id = (int)$_POST['id'];
        $pw = (string)($_POST['password'] ?? '');
        
        if (strlen($pw) < 8) {
            $error = 'Password must be at least 8 characters.';
        } else {
            $hash = password_hash($pw, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('UPDATE admins SET password=? WHERE id=?');
            $stmt->bind_param('si', $hash, $id);
            $stmt->execute();
            $stmt->close();
            
            logAction($conn, "Reset password for admin #$id");
            flash('success', 'Password reset.');
            redirectTo('admin/users.php');
        }
    } elseif ($action === 'toggle') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare('UPDATE admins SET active=1-active WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        
        logAction($conn, "Toggled admin #$id");
        redirectTo('admin/users.php');
    }
}

$res = $conn->query('SELECT id,username,full_name,role,active,created_at FROM admins ORDER BY id');

adminPageStart('Admin accounts', 'users');
?>

<?php if ($error): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>

<div class="admin-card">
    <h2>Add administrator</h2>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        <input type="hidden" name="action" value="add">
        
        <div class="form-grid">
            <div class="field">
                <label>Username *</label>
                <input name="username" required>
            </div>
            
            <div class="field">
                <label>Full name</label>
                <input name="full_name">
            </div>
            
            <div class="field">
                <label>Temporary/default password *</label>
                <input type="password" name="password" required minlength="8">
            </div>
            
            <div class="field">
                <label>Role</label>
                <select name="role">
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super admin</option>
                </select>
            </div>
        </div>
        
        <button class="btn btn-primary">Create admin</button>
    </form>
</div>

<div style="height:15px"></div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Reset password</th>
                <th>Action</th>
            </tr>
            <?php while ($a = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= e($a['username']) ?></td>
                    <td><?= e($a['full_name']) ?></td>
                    <td><?= e($a['role']) ?></td>
                    <td><?= $a['active'] ? 'Active' : 'Disabled' ?></td>
                    <td>
                        <form method="post" class="actions">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="action" value="password">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <input type="password" name="password" minlength="8" placeholder="New password" required>
                            <button class="btn btn-primary">Reset</button>
                        </form>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <button class="btn btn-muted"><?= $a['active'] ? 'Disable' : 'Enable' ?></button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>