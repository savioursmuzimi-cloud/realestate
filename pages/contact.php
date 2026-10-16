<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
$settings = getSiteSettings(getDBConnection());
$pageTitle = 'Contact HomeLink-KE';
require __DIR__ . '/../includes/header.php';

// Safe fallback if whatsappUrl() does not exist in functions.php
$waNumber = preg_replace('/[^0-9]/', '', (string)($settings['whatsapp_number'] ?? ''));
$contactWaUrl = $waNumber !== '' ? 'https://wa.me/' . $waNumber : '';
if (function_exists('whatsappUrl') && !empty($settings['whatsapp_number'])) {
    $contactWaUrl = whatsappUrl((string)$settings['whatsapp_number']);
}
?>
<div class="back-navigation">
    <button type="button" class="back-button" onclick="goBack()">
        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
    </button>
</div>
<section class="section">
    <div class="container">
        <div class="form-wrap" style="max-width:850px">
            <div class="kicker">CONTACT</div>
            <h1>Talk to HomeLink-KE</h1>
            <p class="muted">All public property and BnB communication is handled through HomeLink-KE.</p>
            <div class="property-grid" style="margin-top:25px">
                <div class="admin-card">
                    <i class="fa-solid fa-phone"></i>
                    <h3>Phone</h3>
                    <p><?= e($settings['contact_phone']) ?></p>
                </div>
                <div class="admin-card">
                    <i class="fa-solid fa-envelope"></i>
                    <h3>Email</h3>
                    <p><?= e($settings['contact_email']) ?></p>
                </div>
                <div class="admin-card">
                    <i class="fa-solid fa-location-dot"></i>
                    <h3>Office</h3>
                    <p><?= e($settings['office_location']) ?></p>
                </div>
            </div>
            <?php if ($contactWaUrl !== '' || count(socialMediaLinks($settings)) > 0): ?>
                <div class="contact-social-block">
                    <h2>Connect with us</h2>
                    <p class="muted">Follow HomeLink-KE or contact us through your preferred channel.</p>
                    <div class="social-icons contact-social-icons">
                        <?php foreach (socialMediaLinks($settings) as $social): ?>
                            <a class="social-icon" href="<?= e($social['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Visit HomeLink-KE on <?= e($social['label']) ?>" title="<?= e($social['label']) ?>"><i class="<?= e($social['icon']) ?>" aria-hidden="true"></i><span><?= e($social['label']) ?></span></a>
                        <?php endforeach; ?>
                        <?php if ($contactWaUrl !== ''): ?>
                            <a class="social-icon whatsapp-icon" href="<?= e($contactWaUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat with HomeLink-KE on WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i><span>WhatsApp</span></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div style="margin-top:25px">
                <a class="btn btn-primary" href="<?= appBaseUrl() ?>/forms/inquiry.php">Send an inquiry</a>
            </div>
        </div>
    </div>
</section>
<script>
function goBack() {
    // Check if there's history within the same site, otherwise fallback immediately
    if (document.referrer && document.referrer.indexOf(window.location.origin) === 0) {
        window.history.back();
    } else {
        window.location.href = "<?= appBaseUrl() ?>/";
    }
}
</script><?php require __DIR__ . '/../includes/footer.php'; ?>