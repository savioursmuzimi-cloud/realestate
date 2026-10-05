<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)$_POST['id'];
    $status = post('status');
    
    $stmt = $conn->prepare('UPDATE bnb_requests SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
    
    logAction($conn, "Updated BnB request #$id to $status");
    flash('success', 'BnB request updated.');
    redirectTo('admin/bnb-requests.php');
}

$res = $conn->query("SELECT b.*,p.title property_title FROM bnb_requests b LEFT JOIN properties p ON p.id=b.property_id ORDER BY b.created_at DESC");

adminPageStart('BnB requests', 'bnb');
?>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Guest</th>
                <th>BnB</th>
                <th>Stay</th>
                <th>Guests</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <strong><?= e($r['full_name']) ?></strong><br>
                        <?= e($r['phone']) ?><br>
                        <small><?= e($r['email']) ?></small>
                    </td>
                    <td><?= e($r['property_title'] ?? 'Deleted listing') ?></td>
                    <td><?= e($r['check_in']) ?> → <?= e($r['check_out']) ?></td>
                    <td><?= e((string)$r['guests']) ?></td>
                    <td><?= e($r['status']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <select name="status">
                                <option>pending</option>
                                <option>confirmed</option>
                                <option>checked_in</option>
                                <option>completed</option>
                                <option>cancelled</option>
                            </select>
                            <button class="btn btn-primary" style="margin-top:5px">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>