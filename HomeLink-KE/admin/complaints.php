<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)$_POST['id'];
    $status = post('status');
    
    $stmt = $conn->prepare('UPDATE complaints SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
    
    flash('success', 'Complaint updated.');
    redirectTo('admin/complaints.php');
}

$res = $conn->query('SELECT * FROM complaints ORDER BY created_at DESC');

adminPageStart('Complaints', 'complaints');
?>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Client</th>
                <th>Complaint</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= e($r['full_name']) ?><br>
                        <?= e($r['phone']) ?><br>
                        <?= e($r['email']) ?>
                    </td>
                    <td><?= nl2br(e($r['details'])) ?></td>
                    <td><?= e($r['status']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <select name="status">
                                <option>open</option>
                                <option>in_progress</option>
                                <option>resolved</option>
                                <option>closed</option>
                            </select>
                            <button class="btn btn-primary">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>