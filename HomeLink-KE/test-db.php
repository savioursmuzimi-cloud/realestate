<?php 
require_once __DIR__ . '/config/database.php';

try {
    $c = getDBConnection();
    $r = $c->query('SELECT COUNT(*) c FROM properties')->fetch_assoc();
    echo '<h2 style="font-family:Arial;color:green">Database connection successful.</h2><p>homelink_ke is connected. Properties: ' . (int)$r['c'] . '</p>';
} catch (Throwable $e) {
    http_response_code(500);
    echo '<h2 style="font-family:Arial;color:#b91c1c">Database connection failed.</h2><p>' . htmlspecialchars($e->getMessage()) . '</p>';
} 
?>