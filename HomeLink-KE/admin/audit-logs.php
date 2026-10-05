<?php 
require_once __DIR__ . '/../includes/admin.php';

$res = $conn->query('SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT 300');

adminPageStart('Audit logs', 'logs');
?>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Time</th>
                <th>Actor</th>
                <th>Action</th>
            </tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= e($r['created_at']) ?></td>
                    <td><?= e($r['actor']) ?></td>
                    <td><?= e($r['action']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>