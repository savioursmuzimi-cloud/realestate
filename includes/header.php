<?php
require_once __DIR__ . '/functions.php';
$settings = $settings ?? getSiteSettings();
$siteName = $settings['site_name'] ?? 'HomeLink-KE';
$tagline = $settings['site_tagline'] ?? '-KE';
$logo = assetUrl((string)($settings['site_logo'] ?? ''));
$current = basename($_SERVER['PHP_SELF'] ?? 'index.php');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($pageTitle ?? ($siteName . ' ' . $tagline)) ?></title>
    <meta name="description" content="<?= e($metaDescription ?? 'Verified rentals and BnB stays in Kisii through HomeLink-KE.') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="icon" type="image/png" href="<?= $logo ?: (appBaseUrl() . '/assets/folder/hero-luxury-building.jpg') ?>">
    <link rel="stylesheet" href="<?= appBaseUrl() ?>/assets/folder/style.css">
</head>

<body>
    <header class="site-header">
        <div class="topbar">
            <div>Verified homes & stays in Kisii</div>
            <div class="top-contact">
                <?php if (!empty($settings['contact_phone'])): ?>
                    <a href="tel:<?= e($settings['contact_phone']) ?>"><i class="fa-solid fa-phone"></i> <?= e($settings['contact_phone']) ?></a>
                <?php endif; ?>
                <?php if (!empty($settings['whatsapp_number'])): ?>
                    <?php $waNumber = preg_replace('/\D+/', '', (string)$settings['whatsapp_number']); ?>
                    <a class="top-whatsapp" href="https://wa.me/<?= e($waNumber) ?>" target="_blank" rel="noopener noreferrer" aria-label="Chat with HomeLink-KE on WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i><span>Chat on WhatsApp</span></a>
                <?php endif; ?>
            </div>
        </div>
        <nav class="navbar container">
            <a class="brand" href="<?= appBaseUrl() ?>/index.php">
                <?php if ($logo && file_exists(__DIR__ . '/../' . ltrim((string)$settings['site_logo'], './'))): ?>
                    <img src="<?= $logo ?>" alt="<?= e($siteName) ?> logo">
                <?php else: ?>
                    <span class="brand-mark"><i class="fa-solid fa-house"></i></span>
                <?php endif; ?>
                <span><?= e($siteName) ?><b><?= e($tagline) ?></b></span>
            </a>
            <button class="nav-toggle" type="button" aria-label="Open navigation menu" aria-expanded="false" aria-controls="primary-navigation"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
            <div class="nav-links" id="primary-navigation">
                <a class="<?= ($current === 'index.php' ? 'active' : '') ?>" href="<?= appBaseUrl() ?>/index.php">Home</a>
                <a href="<?= appBaseUrl() ?>/properties/index.php">Properties</a>
                <a href="<?= appBaseUrl() ?>/pages/about.php">About</a>
                <a href="<?= appBaseUrl() ?>/pages/privacy.php">Privacy</a>

                <a href="<?= appBaseUrl() ?>/pages/contact.php">Contact</a>
                <a class="nav-cta" href="<?= appBaseUrl() ?>/forms/landlord-request.php">List a Property</a>
            </div>
        </nav>
    </header>
    <?php if (($settings['maintenance_mode'] ?? 0) && !isAdminLoggedIn()): ?>
        <div class="notice warning"><i class="fa-solid fa-screwdriver-wrench"></i> Website maintenance mode is active. Some services may be temporarily unavailable.</div>
    <?php endif; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.querySelector('.nav-toggle');
        const nav = document.querySelector('.nav-links');
        if (!toggle || !nav) return;
        function closeNav() { document.body.classList.remove('nav-open'); toggle.setAttribute('aria-expanded', 'false'); toggle.setAttribute('aria-label', 'Open navigation menu'); const icon = toggle.querySelector('i'); if (icon) icon.className = 'fa-solid fa-bars'; }
        toggle.addEventListener('click', function () {
            const open = document.body.classList.toggle('nav-open');
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
            const icon = toggle.querySelector('i'); if (icon) icon.className = open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
        });
        nav.querySelectorAll('a').forEach(function (link) { link.addEventListener('click', closeNav); });
        document.addEventListener('keydown', function (event) { if (event.key === 'Escape') closeNav(); });
        window.addEventListener('resize', function () { if (window.innerWidth > 760) closeNav(); });
    });
    </script>
    <main>