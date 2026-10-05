<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$message = '';$error = '';

function colExists(mysqli $c, string $table, string$col): bool {
    $t =$c->real_escape_string($table);$f = $c->real_escape_string($col);
    $r =$c->query("SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='" . DB_NAME . "' AND TABLE_NAME='$t' AND COLUMN_NAME='$f' LIMIT 1");
    return $r &&$r->num_rows > 0;
}

function addCol(mysqli $c, string$table, string $col, string$definition): void {
    if (!colExists($c,$table, $col)) {$c->query("ALTER TABLE `$table` ADD COLUMN `$col` $definition");
    }
}

try {
    $root = new mysqli(DB_HOST, DB_USER, DB_PASS);
    if ($root->connect_errno) {
        throw new RuntimeException($root->connect_error);
    }
    
    $root->set_charset('utf8mb4');$root->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $root->select_db(DB_NAME);

    // Bring older HomeLink databases up to the current schema before applying seed data.
    addCol($root, 'admins', 'full_name', 'VARCHAR(100) DEFAULT NULL');
    addCol($root, 'admins', 'role', "ENUM('super_admin','admin') NOT NULL DEFAULT 'admin'");
    addCol($root, 'admins', 'active', 'TINYINT(1) NOT NULL DEFAULT 1');
    addCol($root, 'admins', 'updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

    addCol($root, 'properties', 'updated_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

    addCol($root, 'viewing_requests', 'status', "ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");

    addCol($root, 'inquiries', 'property_id', 'INT DEFAULT NULL');
    addCol($root, 'inquiries', 'status', "ENUM('new','read','closed') NOT NULL DEFAULT 'new'");

    addCol($root, 'complaints', 'status', "ENUM('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open'");

    addCol($root, 'landlord_requests', 'status', "ENUM('new','contacted','listed','closed') NOT NULL DEFAULT 'new'");

    // Create all missing tables and seed defaults.
    $sql = file_get_contents(__DIR__ . '/homelink_ke (2).sql');$sql = preg_replace('/^CREATE DATABASE.*$/mi', '', $sql);$sql = preg_replace('/^USE homelink_ke;$/mi', '', $sql);
    
    if (!$root->multi_query($sql)) {
        throw new RuntimeException($root->error);
    }
    
    while ($root->more_results() &&$root->next_result()) {}

    // Upgrade the original plaintext default admin only if its password has not already been hashed/changed.
    $r =$root->query("SELECT password FROM admins WHERE username='admin' LIMIT 1");
    if ($r && ($a = $r->fetch_assoc()) && strpos((string)$a['password'], '$2y$') !== 0) {
        $hash = '$2y$12$xJ/FlZMOB0KN/go5rE7Q7etB2.je0Qg9nY985E6MdF3FxDTT6SkH2';
        $stmt =$root->prepare('UPDATE admins SET password=?,role=\'super_admin\',active=1 WHERE username=\'admin\'');
        $stmt->bind_param('s',$hash);
        $stmt->execute();$stmt->close();
    }

    $message = 'Database setup/migration completed successfully. Existing HomeLink data was preserved.';
} catch (Throwable $e) {
    $error =$e->getMessage();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>HomeLink-KE Database Setup</title>
    <link rel="stylesheet" href="assets/folder/style.css">
</head>
<body>
    <section class="section">
        <div class="form-wrap">
            <div class="kicker">XAMPP SETUP</div>
            <h1>HomeLink-KE database</h1>
            
            <?php if ($message): ?>
                <div class="alert success"><?= htmlspecialchars($message) ?></div>
                <a class="btn btn-primary" href="index.php">Open HomeLink-KE</a>
            <?php else: ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
                <p>Make sure Apache and MySQL are running in XAMPP, then try again.</p>
            <?php endif; ?>
            
            <p class="muted" style="font-size:12px;margin-top:20px">Database: homelink_ke · Default admin: admin / admin123</p>
        </div>
    </section>
</body>
</html>