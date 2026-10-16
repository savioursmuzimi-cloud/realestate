<?php
require_once __DIR__ . '/functions.php';
checkAdminLoggedIn();
$conn = $conn ?? getDBConnection();
$adminName = $_SESSION['admin_username'] ?? 'admin';
$adminRole = (string)($_SESSION['admin_role'] ?? 'agent');
$isPrivilegedAdmin = ($adminRole === 'super_admin');

function adminPageStart(string $title, string $active = ''): void
{
    global $adminName, $adminRole, $isPrivilegedAdmin;
    $items = [
        ['dashboard.php', 'dashboard', 'fa-gauge', 'Dashboard', true],
        ['properties.php', 'properties', 'fa-building', 'Properties', true],
        ['add-property.php', 'add', 'fa-plus', 'Add property', true],
        ['landlords.php', 'landlords', 'fa-user-tie', 'Landlord requests', true],
        ['viewing-requests.php', 'viewings', 'fa-calendar-check', 'Viewings', true],
        ['bnb-requests.php', 'bnb', 'fa-bed', 'BnB requests', true],
        ['inquiries.php', 'inquiries', 'fa-envelope', 'Inquiries', true],
        ['complaints.php', 'complaints', 'fa-circle-exclamation', 'Complaints', true],
        ['service-fees.php', 'fees', 'fa-coins', 'Service fees', $isPrivilegedAdmin],
        ['users.php', 'users', 'fa-users-gear', 'Admins', $isPrivilegedAdmin],
        ['account.php', 'account', 'fa-key', 'My password', true],
        ['settings.php', 'settings', 'fa-gear', 'Website settings', $isPrivilegedAdmin],
        ['audit-logs.php', 'logs', 'fa-list-check', 'Audit logs', $isPrivilegedAdmin],
    ];
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
    <title><?= e($title) ?> | HomeLink-KE Admin</title>
    <meta name="theme-color" content="#0b1220">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= appBaseUrl() ?>/assets/folder/style.css">
</head>
<body class="admin-body">
    <div class="admin-sidebar-backdrop" data-admin-close></div>
    <div class="admin-shell">
        <aside class="admin-sidebar" id="admin-sidebar" aria-label="Admin navigation">
            <div class="admin-logo">HomeLink<span>-KE</span><small>ADMIN CONSOLE</small></div>
            <nav class="admin-nav">
                <?php foreach ($items as $it): if (!$it[4]) continue; ?>
                    <a class="<?= $active === $it[1] ? 'active' : '' ?>" href="<?= appBaseUrl() ?>/admin/<?= $it[0] ?>"><i class="fa-solid <?= $it[2] ?>" aria-hidden="true"></i><span><?= e($it[3]) ?></span></a>
                <?php endforeach; ?>
            </nav>
            <div class="admin-sidebar-bottom">
                <a href="<?= appBaseUrl() ?>/index.php"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> View website</a>
                <a class="logout-link" href="<?= appBaseUrl() ?>/admin/logout.php"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Logout</a>
            </div>
        </aside>
        <section class="admin-main">
            <header class="admin-top">
                <div class="admin-heading-group">
                    <button class="admin-menu-toggle" type="button" aria-label="Open admin menu" aria-controls="admin-sidebar" aria-expanded="false"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
                    <div><div class="kicker">HomeLink-KE Admin</div><h1><?= e($title) ?></h1></div>
                </div>
                <div class="muted admin-user-badge"><i class="fa-solid fa-user-shield" aria-hidden="true"></i> <?= e($adminName) ?> <span class="role-pill"><?= e(str_replace('_', ' ', $adminRole)) ?></span></div>
            </header>
            <script>
            (() => {
                const body = document.body;
                const toggle = document.querySelector('.admin-menu-toggle');
                const backdrop = document.querySelector('[data-admin-close]');
                const closeMenu = () => {
                    body.classList.remove('admin-sidebar-open');
                    if (toggle) { toggle.setAttribute('aria-expanded', 'false'); toggle.setAttribute('aria-label', 'Open admin menu'); }
                };
                if (toggle) toggle.addEventListener('click', () => {
                    const open = body.classList.toggle('admin-sidebar-open');
                    toggle.setAttribute('aria-expanded', String(open));
                    toggle.setAttribute('aria-label', open ? 'Close admin menu' : 'Open admin menu');
                });
                if (backdrop) backdrop.addEventListener('click', closeMenu);
                document.querySelectorAll('.admin-nav a').forEach(link => link.addEventListener('click', closeMenu));
                document.addEventListener('keydown', event => { if (event.key === 'Escape') closeMenu(); });
            })();
            </script>
    <?php
}

function adminPageEnd(): void
{
    $flash = getFlash();
    if ($flash): ?>
        <div class="toast <?= e($flash['type']) ?>" role="status" style="display:block"><?= e($flash['message']) ?></div>
    <?php endif; ?>
        </section>
    </div>
</body>
</html>
<?php }
