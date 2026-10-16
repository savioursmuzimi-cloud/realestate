<?php
declare(strict_types=1);

// InfinityFree MySQL Database Configurations
define('DB_HOST', 'sql101.infinityfree.com');
define('DB_USER', 'if0_43091204');
define('DB_PASS', 'CaFNlT1HAP3F'); 
define('DB_NAME', 'if0_43091204_homelink_ke');
define('DB_PORT', 3306);

function getDBConnection(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }

    mysqli_report(MYSQLI_REPORT_OFF);

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($conn->connect_errno) {
        // Output detailed error for administrative setup, or generic message in production
        throw new RuntimeException('Database connection failed: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}