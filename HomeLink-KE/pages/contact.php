<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$settings = getSiteSettings(getDBConnection());
$pageTitle = 'Contact HomeLink-KE';
require __DIR__ . '/../includes/header.php';
?>

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
            
            <div style="margin-top:25px">
                <a class="btn btn-primary" href="<?= appBaseUrl() ?>/forms/inquiry.php">Send an inquiry</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>