<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)$_POST['id'];
    $status = post('status');
    
    $stmt = $conn->prepare('UPDATE viewing_requests SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
    
    logAction($conn, "Updated viewing request #$id to $status");
    flash('success', 'Viewing request updated.');
    redirectTo('admin/viewing-requests.php');
}

$res = $conn->query("SELECT v.*,p.title property_title FROM viewing_requests v LEFT JOIN properties p ON p.id=v.property_id ORDER BY v.created_at DESC");

adminPageStart('Viewing requests', 'viewings');
?>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Client</th>
                <th>Property</th>
                <th>Date</th>
                <th>Notes</th>
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
                    <td><?= e($r['property_title'] ?? 'Deleted property') ?></td>
                    <td><?= e($r['view_date']) ?></td>
                    <td><?= e($r['notes']) ?></td>
                    <td><?= e($r['status']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <select name="status">
                                <option>pending</option>
                                <option>confirmed</option>
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