<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)$_POST['id'];
    $status = post('status');
    
    $stmt = $conn->prepare('UPDATE landlord_requests SET status=? WHERE id=?');
    $stmt->bind_param('si', $status, $id);
    $stmt->execute();
    $stmt->close();
    
    logAction($conn, "Updated landlord request #$id to $status");
    flash('success', 'Request updated.');
    redirectTo('admin/landlords.php');
}

$res = $conn->query('SELECT * FROM landlord_requests ORDER BY created_at DESC');

adminPageStart('Landlord requests', 'landlords');
?>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Owner</th>
                <th>Property</th>
                <th>Location</th>
                <th>Details</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <strong><?= e($r['landlord_name']) ?></strong><br>
                        <?= e($r['phone']) ?><br>
                        <small><?= e($r['email']) ?></small>
                    </td>
                    <td><?= e($r['property_type']) ?></td>
                    <td><?= e($r['location']) ?></td>
                    <td><?= e($r['details']) ?></td>
                    <td><?= e($r['status']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <select name="status">
                                <option>new</option>
                                <option>contacted</option>
                                <option>listed</option>
                                <option>closed</option>
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