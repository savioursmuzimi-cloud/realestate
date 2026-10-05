<?php 
$settings = $settings ?? getSiteSettings();
$flash = getFlash();?>
</main>
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="footer-brand">HomeLink<span>-KE</span></div>
            <p><?= e($settings['footer_text'] ?? 'Connecting clients to verified homes and stays.') ?></p>
        </div>
        <div>
            <h4>Explore</h4>
            <a href="<?= appBaseUrl() ?>/properties/index.php">Properties</a>
            <a href="<?= appBaseUrl() ?>/pages/about.php">About us</a>
            <a href="<?= appBaseUrl() ?>/pages/contact.php">Contact</a>
        </div>
        <div>
            <h4>Support</h4>
            <a href="<?= appBaseUrl() ?>/forms/inquiry.php">Send an inquiry</a>
            <a href="<?= appBaseUrl() ?>/forms/complaint.php">Make a complaint</a>            <a href="<?= appBaseUrl() ?>/forms/landlord-request.php">Landlord request</a>        </div>
        <div>
            <h4>Contact</h4>
            <?php if (!empty($settings['contact_phone'])): ?>
                <a href="tel:<?= e($settings['contact_phone']) ?>"><?= e($settings['contact_phone']) ?></a>
            <?php endif; ?>
            <?php if (!empty($settings['contact_email'])): ?>
                <a href="mailto:<?= e($settings['contact_email']) ?>"><?= e($settings['contact_email']) ?></a>
            <?php endif; ?>
            <span><?= e($settings['office_location'] ?? $settings['contact_location'] ?? 'Kisii CBD, Kenya') ?></span>
        </div>
    </div>
    <div class="footer-bottom container">
        <span>© <?= date('Y') ?> HomeLink-KE. All rights reserved.</span>
        <div>
            <a href="<?= appBaseUrl() ?>/pages/privacy.php">Privacy</a>
            <a href="<?= appBaseUrl() ?>/pages/terms.php">Terms</a>
            <a class="admin-footer-link" href="<?= appBaseUrl() ?>/admin/login.php">                <i class="fa-solid fa-lock"></i> Admin
            </a>
        </div>
    </div>
</footer>

<?php if ($flash): ?>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const b = document.createElement('div');
            b.className = 'toast <?= $flash['type'] === 'success' ? 'success' : 'error' ?>';
            b.textContent = <?= json_encode($flash['message']) ?>;
            document.body.appendChild(b);
            setTimeout(() => b.remove(), 4500);
        });
    </script>
<?php endif; ?>
</body>
</html>