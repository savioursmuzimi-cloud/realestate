<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)($_POST['id'] ?? 0);
    $action = post('action');
    
    if ($id && in_array($action, ['verify', 'hide', 'show', 'delete'], true)) {
        if ($action === 'delete') {
            deleteProperty($conn, $id);
            logAction($conn, "Deleted property #$id");
        } else {
            $field = $action === 'verify' ? 'is_verified' : 'is_hidden';
            $value = in_array($action, ['verify', 'show'], true) ? 1 : 0;
            
            if ($action === 'hide')
                $value = 1;
            if ($action === 'show')
                $value = 0;
                
            $stmt = $conn->prepare("UPDATE properties SET $field=? WHERE id=?");
            $stmt->bind_param('ii', $value, $id);
            $stmt->execute();
            $stmt->close();
            
            logAction($conn, ucfirst($action) . " property #$id");
        }
        
        flash('success', 'Property updated.');
        redirectTo('admin/properties.php');
    }
}

$res = $conn->query('SELECT p.* FROM properties p ORDER BY p.created_at DESC,p.id DESC');

adminPageStart('Properties', 'properties');
?>

<div class="admin-card">
    <div class="admin-top">
        <h2>All listings</h2>
        <a class="btn btn-primary" href="<?= appBaseUrl() ?>/admin/add-property.php">
            <i class="fa-solid fa-plus"></i> Add property
        </a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Property</th>
                <th>Category</th>
                <th>Price</th>
                <th>Verification</th>
                <th>Visibility</th>
                <th>Actions</th>
            </tr>
            <?php while ($p = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <strong><?= e($p['title']) ?></strong><br>
                        <small><?= e($p['location']) ?></small>
                    </td>
                    <td><?= e($p['category']) ?></td>
                    <td><?= formatKSH($p['price']) ?></td>
                    <td><?= $p['is_verified'] ? '<span class="badge" style="position:static;display:inline-block">Verified</span>' : 'Not verified' ?></td>
                    <td><?= $p['is_hidden'] ? 'Hidden' : 'Visible' ?></td>
                    <td>
                        <div class="actions">
                            <a class="btn btn-muted" href="<?= appBaseUrl() ?>/admin/edit-property.php?id=<?= $p['id'] ?>">Edit</a>
                            <form method="post">
                                <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                
                                <?php if (!$p['is_verified']): ?>
                                    <button name="action" value="verify" class="btn btn-primary">Verify</button>
                                <?php endif; ?>
                                
                                <?php if ($p['is_hidden']): ?>
                                    <button name="action" value="show" class="btn btn-muted">Show</button>
                                <?php else: ?>
                                    <button name="action" value="hide" class="btn btn-muted">Hide</button>
                                <?php endif; ?>
                                
                                <button name="action" value="delete" class="btn btn-danger" onclick="return confirm('Delete this property?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>