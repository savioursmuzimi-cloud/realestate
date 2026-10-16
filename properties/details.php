<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$conn = getDBConnection();$id = (int)($_GET['id'] ?? 0);$property = getProperty($conn,$id);

if (!$property) {
    header("Location: " . appBaseUrl() . "/index.php");
    exit;
}

// 1. Retrieve uploaded photos from property_photos table
$photos = getPropertyPhotos($conn,$id);

// 2. Resolve the main banner image path
$mainImage = '';
if (!empty($photos)) {
    $mainImage = assetUrl($photos[0]['photo_path']);
} else {
    $mainImage = propertyImage($property);
}

$pageTitle = e($property['title']) . ' | HomeLink-KE';

require __DIR__ . '/../includes/header.php';
?>

<div class="back-navigation">
    <button type="button" class="back-button" onclick="goBack()">
        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
    </button>
</div>


<div class="container section">
    <div class="detail-layout">
        <div class="detail-gallery">
            <!-- Main Large Banner Container -->
            <div class="gallery-main" id="gallery-banner-container">
                <?php if (!empty($mainImage)): ?>
                    <img id="main-banner-img" src="<?= e($mainImage) ?>" alt="<?= e($property['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div class="placeholder"><i class="fa-solid fa-house"></i></div>
                <?php endif; ?>
            </div>

            <!-- Thumbnail Selector Strip -->
            <?php if (!empty($photos)): ?>
                <div class="gallery-thumbnails mt-3 d-flex gap-2" style="margin-top: 15px; display: flex; gap: 10px;">
                    <?php foreach ($photos as $idx =>$photo): ?>
                        <?php $fullUrl = assetUrl($photo['photo_path']); ?>
                        <img src="<?= e($fullUrl) ?>" 
                             alt="Photo <?= $idx + 1 ?>" 
                             class="thumb-img"
                             onclick="switchBanner('<?= e($fullUrl) ?>', this)"
                             style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid <?= $idx === 0 ? '#0b3b2c' : '#e3dec9' ?>;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Property Info Sidebar -->
        <div class="detail-card">
            <h2><?= e($property['title']) ?></h2>
            <div class="muted mb-2"><i class="fa-solid fa-location-dot"></i> <?= e($property['location']) ?></div>
            <div class="price mb-3"><?= formatKSH($property['price']) ?></div>
            <p><?= nl2br(e($property['description'] ?? 'No description provided.')) ?></p>
            <a href="<?= appBaseUrl() ?>/forms/viewing-request.php?property_id=<?= $property['id'] ?>" class="btn btn-primary w-100 mt-3">Request a Viewing</a>
        </div>
    </div>
</div>

<script>
function switchBanner(imgUrl, el) {
    const bannerContainer = document.getElementById('gallery-banner-container');
    
    // Replace placeholder icon with img tag if needed
    bannerContainer.innerHTML = `<img id="main-banner-img" src="${imgUrl}" alt="Property Image" style="width: 100%; height: 100%; object-fit: cover;">`;
    
    // Highlight selected thumbnail border
    document.querySelectorAll('.thumb-img').forEach(thumb => {
        thumb.style.borderColor = '#e3dec9';
    });
    if (el) {
        el.style.borderColor = '#0b3b2c';
    }
}

function goBack() {
    if (window.history.length > 1 && document.referrer) {
        const previousURL = new URL(document.referrer);

        if (previousURL.origin === window.location.origin) {
            window.history.back();
            return;
        }
    }

    window.location.href = "<?= appBaseUrl() ?>/";
}



</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>