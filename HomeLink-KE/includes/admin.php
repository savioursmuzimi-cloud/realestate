<?php
require_once __DIR__ . '/functions.php';
checkAdminLoggedIn();

$conn = $conn ?? getDBConnection();
$adminName = $_SESSION['admin_username'] ?? 'admin';

function adminPageStart(string $title, string $active = ''): void {
    global $adminName;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($title) ?> | HomeLink-KE Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= appBaseUrl() ?>/assets/folder/style.css">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                HomeLink<span>-KE</span>
                <small style="display:block;font-size:10px;color:#64748b;margin-top:4px">ADMIN CONSOLE</small>
            </div>
            <nav class="admin-nav">
                <?php 
                $items = [
                    ['dashboard.php', 'dashboard', 'fa-gauge', 'Dashboard'],
                    ['properties.php', 'properties', 'fa-building', 'Properties'],
                    ['add-property.php', 'add', 'fa-plus', 'Add property'],
                    ['landlords.php', 'landlords', 'fa-user-tie', 'Landlord requests'],
                    ['viewing-requests.php', 'viewings', 'fa-calendar-check', 'Viewings'],
                    ['bnb-requests.php', 'bnb', 'fa-bed', 'BnB requests'],
                    ['inquiries.php', 'inquiries', 'fa-envelope', 'Inquiries'],
                    ['complaints.php', 'complaints', 'fa-circle-exclamation', 'Complaints'],
                    ['service-fees.php', 'fees', 'fa-coins', 'Service fees'],
                    ['users.php', 'users', 'fa-users-gear', 'Admins'],
                    ['account.php', 'account', 'fa-key', 'My password'],
                    ['settings.php', 'settings', 'fa-gear', 'Website settings'],
                    ['audit-logs.php', 'logs', 'fa-list-check', 'Audit logs']
                ]; 
                foreach ($items as $it): 
                ?>
                    <a class="<?= $active === $it[1] ? 'active' : '' ?>" href="<?= appBaseUrl() ?>/admin/<?= $it[0] ?>">
                        <i class="fa-solid <?= $it[2] ?>"></i><?= $it[3] ?>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div style="margin-top:15px;border-top:1px solid #253149;padding-top:12px">
                <a class="admin-nav" href="<?= appBaseUrl() ?>/index.php" style="display:flex;padding:11px 12px;color:#94a3b8;text-decoration:none;font-size:13px">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> View website
                </a>
                <a class="admin-nav" href="<?= appBaseUrl() ?>/admin/logout.php" style="display:flex;padding:11px 12px;color:#fca5a5;text-decoration:none;font-size:13px">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </aside>
        <section class="admin-main">
            <div class="admin-top">
                <div>
                    <div class="kicker">HomeLink-KE Admin</div>
                    <h1><?= e($title) ?></h1>
                </div>
                <div class="muted">
                    <i class="fa-solid fa-user-shield"></i> <?= e($adminName) ?> · <?= e($_SESSION['admin_role'] ?? 'admin') ?>
                </div>
            </div>
<?php 
}

function adminPageEnd(): void { 
    $flash = getFlash();
    if ($flash): 
?>
        <div class="toast <?= e($flash['type']) ?>" style="display:block"><?= e($flash['message']) ?></div>
    <?php endif; ?>
        </section>
    </div>
</body>
</html>
<?php 
}