<?php 
require_once __DIR__ . '/../includes/admin.php';

$settings = getSiteSettings($conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $siteName = post('site_name');
    $tag = post('site_tagline');
    $phone = post('contact_phone');
    $email = post('contact_email');
    $office = post('office_location');
    $wa = post('whatsapp_number');
    $viewFee = (float)($_POST['viewing_fee'] ?? 0);
    $footer = post('footer_text');
    $maintenance = isset($_POST['maintenance_mode']) ? 1 : 0;
    $facebook = post('facebook_link');
    $twitter = post('twitter_link');
    $instagram = post('instagram_link');
    $paybill = post('mpesa_paybill');
    $account = post('mpesa_account');
    
    if ($siteName === '' || $phone === '' || $email === '') {
        $error = 'Site name, phone and email are required.';
    } else {
        $logo = $settings['site_logo'] ?? '';
        
        if (!empty($_FILES['site_logo']['name']) && ($_FILES['site_logo']['error'] ?? 1) === UPLOAD_ERR_OK) {
            $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['site_logo']['tmp_name']);
            $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
            
            if ($ext) {
                $dir = __DIR__ . '/../uploads/system';
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                $file = 'uploads/system/logo_' . bin2hex(random_bytes(6)) . '.' . $ext;
                if (move_uploaded_file($_FILES['site_logo']['tmp_name'], __DIR__ . '/../' . $file)) {
                    $logo = $file;
                }
            }
        }
        
        $stmt = $conn->prepare('UPDATE site_settings SET site_name=?,site_tagline=?,site_logo=?,contact_phone=?,contact_email=?,office_location=?,whatsapp_number=?,viewing_fee=?,maintenance_mode=?,footer_text=?,facebook_link=?,twitter_link=?,instagram_link=?,mpesa_paybill=?,mpesa_account=? WHERE id=1');
        $stmt->bind_param('sssssssdissssss', $siteName, $tag, $logo, $phone, $email, $office, $wa, $viewFee, $maintenance, $footer, $facebook, $twitter, $instagram, $paybill, $account);
        
        if ($stmt->execute()) {
            logAction($conn, 'Updated website settings');
            flash('success', 'Website settings updated.');
            redirectTo('admin/settings.php');
        } else {
            $error = 'Settings update failed: ' . $conn->error;
        }
        
        $stmt->close();
    }
}

adminPageStart('Website settings', 'settings');
?>

<?php if ($error): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>

<form class="admin-card" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    
    <div class="form-grid">
        <div class="field">
            <label>Site name</label>
            <input name="site_name" value="<?= e($settings['site_name']) ?>">
        </div>
        
        <div class="field">
            <label>Tagline</label>
            <input name="site_tagline" value="<?= e($settings['site_tagline']) ?>">
        </div>
        
        <div class="field">
            <label>Phone</label>
            <input name="contact_phone" value="<?= e($settings['contact_phone']) ?>">
        </div>
        
        <div class="field">
            <label>Email</label>
            <input type="email" name="contact_email" value="<?= e($settings['contact_email']) ?>">
        </div>
        
        <div class="field">
            <label>Office location</label>
            <input name="office_location" value="<?= e($settings['office_location']) ?>">
        </div>
        
        <div class="field">
            <label>WhatsApp number</label>
            <input name="whatsapp_number" value="<?= e($settings['whatsapp_number']) ?>">
        </div>
        
        <div class="field">
            <label>Default viewing fee</label>
            <input type="number" step="0.01" name="viewing_fee" value="<?= e((string)$settings['viewing_fee']) ?>">
        </div>
        
        <div class="field">
            <label>Logo</label>
            <input type="file" name="site_logo" accept="image/jpeg,image/png,image/webp">
        </div>
        
        <div class="field">
            <label>M-Pesa Paybill</label>
            <input name="mpesa_paybill" value="<?= e($settings['mpesa_paybill']) ?>">
        </div>
        
        <div class="field">
            <label>M-Pesa account</label>
            <input name="mpesa_account" value="<?= e($settings['mpesa_account']) ?>">
        </div>
        
        <div class="field full">
            <label>Footer text</label>
            <input name="footer_text" value="<?= e($settings['footer_text']) ?>">
        </div>
        
        <div class="field">
            <label>Facebook</label>
            <input name="facebook_link" value="<?= e($settings['facebook_link']) ?>">
        </div>
        
        <div class="field">
            <label>Instagram</label>
            <input name="instagram_link" value="<?= e($settings['instagram_link']) ?>">
        </div>
        
        <div class="field">
            <label>Twitter/X</label>
            <input name="twitter_link" value="<?= e($settings['twitter_link']) ?>">
        </div>
        
        <div class="field">
            <label>
                <input type="checkbox" name="maintenance_mode" <?= $settings['maintenance_mode'] ? 'checked' : '' ?>> Maintenance mode
            </label>
        </div>
    </div>
    
    <button class="btn btn-primary">Save website settings</button>
</form>

<?php adminPageEnd(); ?>