<?php 
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $id = (int)($_POST['id'] ?? 0);
    $type = post('property_type');
    $feeType = post('fee_type');
    $amount = (float)($_POST['amount'] ?? 0);
    $active = isset($_POST['active']) ? 1 : 0;
    
    if ($id) {
        $stmt = $conn->prepare('UPDATE service_fees SET property_type=?,fee_type=?,amount=?,active=? WHERE id=?');
        $stmt->bind_param('ssdii', $type, $feeType, $amount, $active, $id);
    } else {
        $stmt = $conn->prepare('INSERT INTO service_fees(property_type,fee_type,amount,active) VALUES(?,?,?,?)');
        $stmt->bind_param('ssdi', $type, $feeType, $amount, $active);
    }
    
    if ($stmt->execute()) {
        flash('success', 'Service fee saved.');
    } else {
        flash('error', 'Could not save fee.');
    }
    
    $stmt->close();
    redirectTo('admin/service-fees.php');
}

$res = $conn->query('SELECT * FROM service_fees ORDER BY property_type');

adminPageStart('Service fees', 'fees');
?>

<div class="admin-card">
    <h2>Manage intermediary service fees</h2>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
        
        <div class="form-grid">
            <div class="field">
                <label>Property type</label>
                <input name="property_type" required>
            </div>
            <div class="field">
                <label>Fee type</label>
                <select name="fee_type">
                    <option value="fixed">Fixed amount</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>
            <div class="field">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" required>
            </div>
            <div class="field">
                <label>Active</label>
                <label><input type="checkbox" name="active" checked> Use this fee</label>
            </div>
        </div>
        
        <button class="btn btn-primary">Add fee</button>
    </form>
</div>

<div style="height:15px"></div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Type</th>
                <th>Fee</th>
                <th>Active</th>
                <th>Update</th>
            </tr>
            <?php while ($r = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= e($r['property_type']) ?></td>
                    <td><?= e($r['fee_type']) === 'percentage' ? e($r['amount']) . '%' : formatKSH($r['amount']) ?></td>
                    <td><?= $r['active'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <form method="post" class="actions">
                            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <input name="property_type" value="<?= e($r['property_type']) ?>">
                            
                            <select name="fee_type">
                                <option value="fixed" <?= $r['fee_type'] === 'fixed' ? 'selected' : '' ?>>Fixed</option>
                                <option value="percentage" <?= $r['fee_type'] === 'percentage' ? 'selected' : '' ?>>%</option>
                            </select>
                            
                            <input style="width:100px" type="number" step="0.01" name="amount" value="<?= e((string)$r['amount']) ?>">
                            <label><input type="checkbox" name="active" <?= $r['active'] ? 'checked' : '' ?>> active</label>
                            <button class="btn btn-primary">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>