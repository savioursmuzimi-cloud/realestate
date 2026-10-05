<?php 
require_once __DIR__ . '/../includes/admin.php';

$stats = [];
$queries = [
    'properties' => 'SELECT COUNT(*) c FROM properties',
    'landlords'  => "SELECT COUNT(*) c FROM landlord_requests WHERE status IN ('new','contacted')",
    'viewings'   => "SELECT COUNT(*) c FROM viewing_requests WHERE status='pending'",
    'bnb'        => "SELECT COUNT(*) c FROM bnb_requests WHERE status='pending'",
    'inquiries'  => "SELECT COUNT(*) c FROM inquiries WHERE status='new'",
    'complaints' => "SELECT COUNT(*) c FROM complaints WHERE status IN ('open','in_progress')"
];

foreach ($queries as $k => $sql) {
    $r = $conn->query($sql)->fetch_assoc();
    $stats[$k] = (int)$r['c'];
}

$recent = $conn->query("SELECT p.id,p.title,p.location,p.price,p.category,p.is_verified,p.is_hidden FROM properties p ORDER BY p.created_at DESC LIMIT 8");

adminPageStart('Dashboard', 'dashboard');
?>

<div class="stats-grid">
    <div class="stat">
        <i class="fa-solid fa-building"></i>
        <strong><?= $stats['properties'] ?></strong>
        <span class="muted">Total properties</span>
    </div>
    <div class="stat">
        <i class="fa-solid fa-calendar-check"></i>
        <strong><?= $stats['viewings'] ?></strong>
        <span class="muted">Pending viewings</span>
    </div>
    <div class="stat">
        <i class="fa-solid fa-bed"></i>
        <strong><?= $stats['bnb'] ?></strong>
        <span class="muted">Pending BnB</span>
    </div>
    <div class="stat">
        <i class="fa-solid fa-user-tie"></i>
        <strong><?= $stats['landlords'] ?></strong>
        <span class="muted">Landlord requests</span>
    </div>
</div>

<div style="height:15px"></div>

<div class="stats-grid">
    <div class="stat">
        <i class="fa-solid fa-envelope"></i>
        <strong><?= $stats['inquiries'] ?></strong>
        <span class="muted">New inquiries</span>
    </div>
    <div class="stat">
        <i class="fa-solid fa-circle-exclamation"></i>
        <strong><?= $stats['complaints'] ?></strong>
        <span class="muted">Open complaints</span>
    </div>
</div>

<div style="height:20px"></div>

<div class="admin-card">
    <div class="admin-top">
        <h2>Recent properties</h2>
        <a class="btn btn-primary" href="<?= appBaseUrl() ?>/admin/add-property.php">Add property</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <tr>
                <th>Property</th>
                <th>Type</th>
                <th>Price</th>
                <th>Verified</th>
                <th>Visibility</th>
                <th>Action</th>
            </tr>
            <?php while ($p = $recent->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= e($p['title']) ?><br>
                        <small class="muted"><?= e($p['location']) ?></small>
                    </td>
                    <td><?= e($p['category']) ?></td>
                    <td><?= formatKSH($p['price']) ?></td>
                    <td><?= $p['is_verified'] ? 'Yes' : 'No' ?></td>
                    <td><?= $p['is_hidden'] ? 'Hidden' : 'Public' ?></td>
                    <td>
                        <a class="btn btn-muted" href="<?= appBaseUrl() ?>/admin/edit-property.php?id=<?= $p['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php adminPageEnd(); ?>