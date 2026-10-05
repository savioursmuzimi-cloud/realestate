<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$conn = getDBConnection();
$id = (int)($_GET['id'] ?? 0);
$p = getProperty($conn, $id);

if (!$p || $p['is_hidden'] || !$p['is_verified']) {
    http_response_code(404);
    $pageTitle = 'Property not found | HomeLink-KE';
    require __DIR__ . '/../includes/header.php';
    echo '<section class="section container"><div class="empty"><h2>Property not found</h2><p>This listing is unavailable.</p><a class="btn btn-primary" href="' . appBaseUrl() . '/properties/index.php">Back to properties</a></div></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
} 

$settings = getSiteSettings($conn);
$photos = getPropertyPhotos($conn, $id);
$pageTitle = $p['title'] . ' | HomeLink-KE';
$isBnb = strtolower((string)$p['category']) === 'bnb';

// Fallback logic to resolve uploaded photo display
$mainImage = propertyImage($p);
if (!$mainImage && !empty($photos[0]['photo_path'])) {
    $mainImage = assetUrl($photos[0]['photo_path']);
}

require __DIR__ . '/../includes/header.php';
?>

<section class="container detail-layout">
    <div>
        <div class="gallery-main">
            <?php if ($mainImage): ?>
                <img id="mainPhoto" src="<?= e($mainImage) ?>" alt="<?= e($p['title']) ?>">
            <?php else: ?>
                <div class="placeholder"><i class="fa-solid fa-house"></i></div>
            <?php endif; ?>
        </div>

        <?php if (count($photos) > 0): ?>
            <div style="display:flex;gap:8px;margin-top:10px;overflow:auto">
                <?php foreach ($photos as $ph): $u = assetUrl($ph['photo_path']); ?>
                    <button type="button" onclick="document.getElementById('mainPhoto').src='<?= e($u) ?>'" style="border:1px solid #ddd;background:#fff;padding:0;border-radius:8px;overflow:hidden">
                        <img src="<?= e($u) ?>" style="width:80px;height:60px;object-fit:cover">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="section" style="padding-bottom:0">
            <h2>Description</h2>
            <p><?= nl2br(e($p['description'] ?: 'No description provided.')) ?></p>
            <?php if ($p['amenities']): ?>
                <h3>Amenities</h3>
                <p><?= e($p['amenities']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <aside class="detail-card">
        <span class="badge" style="position:static;display:inline-block"><?= e($p['category'] ?: 'Rental') ?></span>
        <h1 style="margin:15px 0 5px;font-size:30px"><?= e($p['title']) ?></h1>
        <p class="muted"><i class="fa-solid fa-location-dot"></i> <?= e($p['location']) ?></p>
        
        <div class="price"><?= formatKSH($p['price']) ?><?= $isBnb ? ' / night' : '' ?></div>
        
        <div class="meta-row" style="margin:15px 0">
            <?php if ($p['bedrooms'] !== null): ?>
                <span><?= e((string)$p['bedrooms']) ?> bedrooms</span>
            <?php endif; ?>
            <span><?= e($p['furnished_status']) ?></span>
            <span><?= e($p['availability']) ?></span>
        </div>
        
        <p class="muted">Landlord contact details are private. HomeLink-KE handles communication and viewing arrangements.</p>
        
        <a class="btn btn-primary" style="width:100%" href="<?= appBaseUrl() ?>/forms/<?= $isBnb ? 'bnb-request.php' : 'viewing-request.php' ?>?property_id=<?= $p['id'] ?>">
            <i class="fa-solid <?= $isBnb ? 'fa-calendar-days' : 'fa-calendar-check' ?>"></i> <?= $isBnb ? 'Request this BnB' : 'Request a viewing' ?>
        </a>
        
        <a class="btn btn-muted" style="width:100%;margin-top:9px" href="<?= appBaseUrl() ?>/forms/inquiry.php?property_id=<?= $p['id'] ?>">Ask a question</a>
    </aside>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>