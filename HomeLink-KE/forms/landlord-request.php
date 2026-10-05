<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$conn = getDBConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $name = post('landlord_name');
    $phone = post('phone');
    $email = post('email');
    $type = post('property_type');
    $location = post('location');
    $details = post('details');
    
    if ($name === '' || $phone === '' || $location === '') {
        $error = 'Name, phone and location are required.';
    } else {
        $stmt = $conn->prepare('INSERT INTO landlord_requests(landlord_name,phone,email,property_type,location,details) VALUES(?,?,?,?,?,?)');
        $stmt->bind_param('ssssss', $name, $phone, $email, $type, $location, $details);
        
        if ($stmt->execute()) {
            flash('success', 'Thank you. HomeLink-KE has received your property request.');
            redirectTo('index.php');
        } else {
            $error = 'Unable to submit request.';
        }
        
        $stmt->close();
    }
}

$pageTitle = 'List a Property | HomeLink-KE';
require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="form-wrap">
        <div class="kicker">LANDLORDS & PROPERTY OWNERS</div>
        <h1>Let HomeLink-KE handle the listing</h1>
        <p class="muted">Send us the details below. We verify the property and manage the public listing and client communication.</p>
        
        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            
            <div class="form-grid">
                <div class="field">
                    <label>Name *</label>
                    <input name="landlord_name" required>
                </div>
                
                <div class="field">
                    <label>Phone *</label>
                    <input name="phone" required>
                </div>
                
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                
                <div class="field">
                    <label>Property type</label>
                    <select name="property_type">
                        <option>Single Rooms</option>
                        <option>Bedsitters</option>
                        <option>1 Bedroom</option>
                        <option>2 Bedroom</option>
                        <option>Commercial</option>
                        <option>BNB</option>
                        <option>Other</option>
                    </select>
                </div>
                
                <div class="field full">
                    <label>Area / location *</label>
                    <input name="location" placeholder="e.g. Mwembe, Kisii" required>
                </div>
                
                <div class="field full">
                    <label>Property details</label>
                    <textarea name="details" placeholder="Rent, deposit, amenities, availability, etc."></textarea>
                </div>
            </div>
            
            <button class="btn btn-primary">Send property request</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>