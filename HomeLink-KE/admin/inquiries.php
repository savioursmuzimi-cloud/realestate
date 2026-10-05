<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)$_POST['id'];
    $status = post('status');
    
    $stmt = $conn->prepare('UPDATE inquiries SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
    
    flash('success', 'Inquiry updated.');
    redirectTo('admin/inquiries.php');
}

$res = $conn->query("SELECT i.*,p.title property_title FROM inquiries i LEFT JOIN properties p ON p.id=i.property_id ORDER BY i.created_at DESC");

adminPageStart('Inquiries', 'inquiries');
?>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>From</th>
                <th>Subject</th>
                <th>Property</th>
                <th>Message</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= e($r['name']) ?><br>
                        <small><?= e($r['email']) ?></small>
                    </td>
                    <td><?= e($r['subject']) ?></td>
                    <td><?= e($r['property_title'] ?? '—') ?></td>
                    <td><?= e($r['message']) ?></td>
                    <td><?= e($r['status']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <select name="status">
                                <option>new</option>
                                <option>read</option>
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