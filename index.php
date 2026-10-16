
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$conn = getDBConnection();
$settings = getSiteSettings($conn);
$pageTitle = 'Home | HomeLink-KE';

$properties = getProperties($conn);

// Display ALL properties on the homepage instead of limiting them to six.
$featured = $properties;

// Auto-generate dynamic options from properties database
$locations = [];
$categories = [];
$prices = [];

foreach ($properties as $p) {
    if (!empty($p['location'])) {
        $locations[] = $p['location'];
    }

    if (!empty($p['category'])) {
        $categories[] = $p['category'];
    }

    if (!empty($p['price'])) {
        $prices[] = (float) $p['price'];
    }
}

$locations = array_unique($locations);
sort($locations);

$categories = array_unique($categories);
sort($categories);

sort($prices);

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-inner">
        <div class="kicker">
            PREMIUM RENTALS
            <span aria-hidden="true">•</span>
            VERIFIED LISTINGS
            <span aria-hidden="true">•</span>
            KISII COUNTY
        </div>

        <h1>Find a place you’ll love <span>to call home.</span></h1>

        <p>
            Discover verified rentals, apartments, houses, shops and BnB stays in Kisii.
            Safe, simple, and stress-free.
        </p>

        <div class="hero-actions">
            <a class="btn nav-cta" href="<?= appBaseUrl() ?>/properties/index.php">
                <i class="fa-solid fa-magnifying-glass"></i>
                Browse properties
            </a>

            <a class="btn btn-outline" href="<?= appBaseUrl() ?>/forms/viewing-request.php">
                <i class="fa-solid fa-calendar-check"></i>
                Request a viewing
            </a>
        </div>
    </div>
</section>

<section class="container search-panel">
    <form class="search-card" method="get" action="<?= appBaseUrl() ?>/properties/index.php">

        <!-- Dynamic Location Dropdown -->
        <select name="location">
            <option value="">All locations</option>

            <?php foreach ($locations as $loc): ?>
                <option value="<?= e($loc) ?>"><?= e($loc) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Dynamic Category Dropdown -->
        <select name="category">
            <option value="">All property types</option>

            <?php foreach ($categories as $cat): ?>
                <option value="<?= e($cat) ?>"><?= e($cat) ?></option>
            <?php endforeach; ?>
        </select>

        <!-- Dynamic Price Range Dropdown -->
        <select name="price_range">
            <option value="">Any price range</option>
            <option value="0-10000">Up to KSh 10,000</option>
            <option value="10000-25000">KSh 10,000 - 25,000</option>
            <option value="25000-50000">KSh 25,000 - 50,000</option>
            <option value="50000+">Above KSh 50,000</option>
        </select>

        <button class="btn btn-primary" type="submit">
            <i class="fa-solid fa-search"></i>
            Search
        </button>
    </form>
</section>

<section class="section">
    <div class="container">

        <div class="section-head">
            <div>
                <h2>Available now</h2>
                <p>Listings verified and managed by HomeLink-KE.</p>
            </div>

            <a class="btn btn-muted" href="<?= appBaseUrl() ?>/properties/index.php">
                View all
            </a>
        </div>

        <?php if (empty($featured)): ?>

            <div class="empty">
                No properties are available right now.
                Check again soon or
                <a href="<?= appBaseUrl() ?>/forms/inquiry.php">contact us</a>.
            </div>

        <?php else: ?>

            <div class="property-grid">

                <?php foreach ($featured as $p): ?>

                    <article class="property-card">

                        <div class="property-media">
                            <?php $img = propertyImage($p); ?>

                            <?php if ($img): ?>
                                <img
                                    src="<?= e($img) ?>"
                                    alt="<?= e($p['title']) ?>"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="placeholder">
                                    <i class="fa-solid fa-house"></i>
                                </div>
                            <?php endif; ?>

                            <span class="badge">
                                <?= e($p['category'] ?: 'Rental') ?>
                            </span>
                        </div>

                        <div class="property-body">

                            <h3><?= e($p['title']) ?></h3>

                            <div class="muted">
                                <i class="fa-solid fa-location-dot"></i>
                                <?= e($p['location']) ?>
                            </div>

                            <div class="price">
                                <?= formatKSH($p['price']) ?><?= strtolower((string) $p['category']) === 'bnb' ? ' / night' : '' ?>
                            </div>

                            <div class="meta-row">

                                <?php if ($p['bedrooms'] !== null): ?>
                                    <span>
                                        <i class="fa-solid fa-bed"></i>
                                        <?= e((string) $p['bedrooms']) ?> bed
                                    </span>
                                <?php endif; ?>

                                <span>
                                    <i class="fa-solid fa-circle-check"></i>
                                    Verified
                                </span>

                                <span><?= e($p['availability']) ?></span>

                            </div>

                            <div class="card-actions">
                                <a
                                    class="btn btn-primary"
                                    href="<?= appBaseUrl() ?>/properties/details.php?id=<?= (int) $p['id'] ?>"
                                >
                                    View details
                                </a>
                            </div>

                        </div>
                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>

<section class="section" style="background:#fff">
    <div class="container">

        <div class="section-head">
            <div>
                <h2>How HomeLink-KE works</h2>
                <p>
                    A simple intermediary model designed around verification
                    and direct communication.
                </p>
            </div>
        </div>

        <div class="property-grid">

            <div class="admin-card">
                <i class="fa-solid fa-shield-halved" style="font-size:26px;color:var(--primary)"></i>
                <h3>1. We verify</h3>
                <p class="muted">
                    Landlords send their property information to us.
                    We verify it before publishing.
                </p>
            </div>

            <div class="admin-card">
                <i class="fa-solid fa-comments" style="font-size:26px;color:var(--accent)"></i>
                <h3>2. You contact us</h3>
                <p class="muted">
                    You request information or a viewing through HomeLink-KE.
                    Private landlord details stay private.
                </p>
            </div>

            <div class="admin-card">
                <i class="fa-solid fa-key" style="font-size:26px;color:#7c3aed"></i>
                <h3>3. View and decide</h3>
                <p class="muted">
                    You view the place with us. Rent and deposit are paid
                    directly to the landlord after you agree.
                </p>
            </div>

        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
```
