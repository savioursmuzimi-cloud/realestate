<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'homelink_ke';

function getDBConnection(): mysqli {
    static $conn = null;
    if ($conn instanceof mysqli) return $conn;
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_errno) {
        throw new RuntimeException('Database connection failed. Check that MySQL is running in XAMPP and the database homelink_ke exists.');
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
