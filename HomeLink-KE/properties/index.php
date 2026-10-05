<?php 
require_once __DIR__ . '/../config/database.php'; 
require_once __DIR__ . '/../includes/functions.php'; 

$conn = getDBConnection(); 
$settings = getSiteSettings($conn); 
$pageTitle = 'Properties | HomeLink-KE';

$q = trim((string)($_GET['q'] ?? '')); 
$category = trim((string)($_GET['category'] ?? '')); 

$sql = "SELECT p.*, (SELECT photo_path FROM property_photos pp WHERE pp.property_id=p.id ORDER BY pp.id LIMIT 1) gallery_image FROM properties p WHERE p.is_hidden=0 AND p.is_verified=1"; 
$types = '';
$vals = []; 

if ($q !== '') {
    $sql .= ' AND (p.title LIKE ? OR p.location LIKE ? OR p.description LIKE ?)';
    $like = "%$q%";
    $vals = [$like, $like, $like];
    $types = 'sss';
} 

if ($category !== '') {
    $sql .= ' AND p.category=?';
    $vals[] = $category;
    $types .= 's';
}

$sql .= ' ORDER BY p.created_at DESC, p.id DESC';
$stmt = $conn->prepare($sql);

if ($types) {
    $stmt->bind_param($types, ...$vals);
}

$stmt->execute();
$res = $stmt->get_result();
$properties = [];

while ($r = $res->fetch_assoc()) {
    $properties[] = $r;
}

$stmt->close(); 

require __DIR__ . '/../includes/header.php'; 
?>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="kicker">PROPERTY DIRECTORY</div>
                <h1>Available properties</h1>
                <p>Search verified homes, commercial spaces and BnB stays.</p>
            </div>
        </div>

        <form class="search-card" method="get">
            <input name="q" value="<?= e($q) ?>" placeholder="Area, property name or description">
            <select name="category">
                <option value="">All types</option>
                <?php foreach (['Single Rooms', 'Bedsitters', '1 Bedroom', '2 Bedroom', 'Commercial', 'BNB'] as $c): ?>
                    <option <?= ($category === $c ? 'selected' : '') ?>><?= e($c) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary">Search</button>
        </form>

        <div style="height:22px"></div>

        <?php if (!$properties): ?>
            <div class="empty">
                <i class="fa-solid fa-house-circle-xmark"></i>
                <h3>No matching properties</h3>
                <p>Try another area or category, or send us an inquiry.</p>
                <a class="btn btn-primary" href="<?= appBaseUrl() ?>/forms/inquiry.php">Contact HomeLink</a>
            </div>
        <?php else: ?>
            <div class="property-grid">
                <?php foreach ($properties as $p): ?>
                    <article class="property-card">
                        <div class="property-media">
                            <?php $img = propertyImage($p); if ($img): ?>
                                <img src="<?= e($img) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="placeholder"><i class="fa-solid fa-house"></i></div>
                            <?php endif; ?>
                            <span class="badge"><?= e($p['category'] ?: 'Rental') ?></span>
                        </div>
                        <div class="property-body">
                            <h3><?= e($p['title']) ?></h3>
                            <div class="muted"><i class="fa-solid fa-location-dot"></i> <?= e($p['location']) ?></div>
                            <div class="price"><?= formatKSH($p['price']) ?><?= strtolower((string)$p['category']) === 'bnb' ? ' / night' : '' ?></div>
                            <div class="meta-row">
                                <span><?= e($p['availability']) ?></span>
                                <?php if ($p['furnished_status']): ?>
                                    <span><?= e($p['furnished_status']) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="card-actions">
                                <a class="btn btn-primary" href="<?= appBaseUrl() ?>/properties/details.php?id=<?= $p['id'] ?>">View details</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>