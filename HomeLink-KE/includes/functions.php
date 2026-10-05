<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

function e(?string $value): string { 
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function post(string $key, string $default = ''): string { 
    return trim((string)($_POST[$key] ?? $default));
}

function formatKSH(float|int|string $amount): string { 
    return 'KSh ' . number_format((float)$amount, 0);
}

function csrfToken(): string { 
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verifyCsrf(): void { 
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back and try again.');
    } 
}

function flash(string $type, string $message): void { 
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array { 
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function assetUrl(string $path): string {
    $path = trim(str_replace('\\', '/', $path));
    if ($path === '') {
        return '';
    }
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }
    $path = preg_replace('~^\.\./+~', '', $path);
    $path = ltrim($path, '/');
    return appBaseUrl() . '/' . $path;
}

function getSiteSettings(?mysqli $conn = null): array {
    $defaults = [
        'site_name' => 'HomeLink-KE',
        'site_tagline' => '-KE',
        'site_logo' => '',
        'contact_phone' => '+254 712 345 678',
        'contact_email' => 'info@homelink.co.ke',
        'contact_location' => 'Kisii CBD, Kenya',
        'office_location' => 'Kisii CBD, Kenya',
        'whatsapp_number' => '',
        'facebook_link' => '',
        'twitter_link' => '',
        'instagram_link' => '',
        'viewing_fee' => 1000,
        'maintenance_mode' => 0,
        'footer_text' => '© 2026 HomeLink-KE. All rights reserved.'
    ];
    
    try {
        $conn ??= getDBConnection();
        $res = $conn->query("SELECT * FROM site_settings WHERE id=1 LIMIT 1");
        if ($res && ($row = $res->fetch_assoc())) {
            return array_merge($defaults, $row);
        }
    } catch (Throwable $e) {}
    
    return $defaults;
}

function logAction(mysqli $conn, string $action): void {
    $actor = $_SESSION['admin_username'] ?? 'system';
    $stmt = $conn->prepare('INSERT INTO audit_logs (actor, action) VALUES (?,?)');
    if ($stmt) {
        $stmt->bind_param('ss', $actor, $action);
        $stmt->execute();
        $stmt->close();
    }
}

function getProperties(mysqli $conn, bool $includeHidden = false): array {
    $where = $includeHidden ? '' : ' WHERE p.is_hidden=0 AND p.is_verified=1';
    $sql = "SELECT p.*, (SELECT photo_path FROM property_photos pp WHERE pp.property_id=p.id ORDER BY pp.id LIMIT 1) AS gallery_image FROM properties p{$where} ORDER BY p.created_at DESC,p.id DESC";
    $out = []; 
    if ($res = $conn->query($sql)) {
        while ($r = $res->fetch_assoc()) {
            $out[] = $r;
        }
    } 
    return $out;
}

function getProperty(mysqli $conn, int $id): ?array {
    $stmt = $conn->prepare('SELECT * FROM properties WHERE id=? LIMIT 1');
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r ?: null;
}

function getPropertyPhotos(mysqli $conn, int $id): array { 
    $a = [];
    $stmt = $conn->prepare('SELECT * FROM property_photos WHERE property_id=? ORDER BY id');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) {
        $a[] = $r;
    }
    $stmt->close();
    return $a;
}

function propertyImage(array $property): string { 
    return assetUrl((string)($property['gallery_image'] ?? $property['image_url'] ?? ''));
}

function saveUploads(mysqli $conn, int $propertyId, array $files): void {
    if (empty($files['name']) || !is_array($files['name'])) return;
    
    $dir = __DIR__ . '/../uploads/photos';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    
    foreach ($files['name'] as $i => $name) { 
        if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
        if (($files['size'][$i] ?? 0) > 8 * 1024 * 1024) continue;
        
        $tmp = $files['tmp_name'][$i];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
        
        if (!isset($allowed[$mime])) continue;
        
        $filename = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        
        if (move_uploaded_file($tmp, $dir . '/' . $filename)) { 
            $path = 'uploads/photos/' . $filename;
            $stmt = $conn->prepare('INSERT INTO property_photos(property_id,photo_path) VALUES(?,?)');
            $stmt->bind_param('is', $propertyId, $path);
            $stmt->execute();
            $stmt->close();
        } 
    }
}

function deleteProperty(mysqli $conn, int $id): void { 
    $p = getProperty($conn, $id);
    if (!$p) return;
    
    $photos = getPropertyPhotos($conn, $id);
    foreach ($photos as $ph) {
        $file = __DIR__ . '/../' . ltrim((string)$ph['photo_path'], './');
        if (is_file($file)) @unlink($file);
    } 
    
    if (!empty($p['image_url'])) {
        $file = __DIR__ . '/../' . ltrim((string)$p['image_url'], './');
        if (is_file($file)) @unlink($file);
    } 
    
    $stmt = $conn->prepare('DELETE FROM properties WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}