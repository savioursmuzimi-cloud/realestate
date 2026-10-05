<?php 
require_once __DIR__ . '/../includes/admin.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $title = post('title');
    $location = post('location');
    $price = (float)($_POST['price'] ?? 0);
    $category = post('category');
    $status = post('status', 'available');
    $availability = post('availability', 'Available');
    $description = post('description');
    $deposit = post('deposit_info');
    $bedrooms = $_POST['bedrooms'] === '' ? null : (int)$_POST['bedrooms'];
    $furnished = post('furnished_status', 'Unfurnished');
    $amenities = post('amenities');
    $verified = isset($_POST['is_verified']) ? 1 : 0;
    $hidden = isset($_POST['is_hidden']) ? 1 : 0;
    $ln = post('landlord_name');
    $lp = post('landlord_phone');
    $le = post('landlord_email');
    $notes = post('landlord_notes');
    
    if ($title === '' || $location === '' || $price <= 0 || $category === '' || $ln === '' || $lp === '') {
        $error = 'Title, location, price, category, landlord name and landlord phone are required.';
    } else {
        $stmt = $conn->prepare('INSERT INTO properties(title,location,price,category,status,availability,description,deposit_info,bedrooms,furnished_status,amenities,is_verified,is_hidden,landlord_name,landlord_phone,landlord_email,landlord_notes) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('ssdsssssisiiissss', $title, $location, $price, $category, $status, $availability, $description, $deposit, $bedrooms, $furnished, $amenities, $verified, $hidden, $ln, $lp, $le, $notes);
        
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            $stmt->close();
            saveUploads($conn, $id, $_FILES['photos'] ?? []);
            logAction($conn, "Added property #$id");
            flash('success', 'Property added successfully.');
            redirectTo('admin/properties.php');
        } else {
            $error = 'Could not save property: ' . $conn->error;
            $stmt->close();
        }
    }
}

adminPageStart('Add property', 'add');
?>

<?php if ($error): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>

<form class="admin-card" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    
    <div class="form-grid">
        <div class="field">
            <label>Property title *</label>
            <input name="title" required>
        </div>
        
        <div class="field">
            <label>Category *</label>
            <select name="category" required>
                <?php foreach (['Single Rooms', 'Bedsitters', '1 Bedroom', '2 Bedroom', 'Commercial', 'BNB', 'Other'] as $c): ?>
                    <option><?= e($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="field">
            <label>Public location / area *</label>
            <input name="location" placeholder="e.g. Mwembe" required>
        </div>
        
        <div class="field">
            <label>Price *</label>
            <input type="number" step="0.01" min="0" name="price" required>
        </div>
        
        <div class="field">
            <label>Status</label>
            <select name="status">
                <option value="available">Available</option>
                <option value="reserved">Reserved</option>
                <option value="rented">Rented</option>
                <option value="sold">Sold</option>
            </select>
        </div>
        
        <div class="field">
            <label>Availability</label>
            <input name="availability" value="Available">
        </div>
        
        <div class="field">
            <label>Bedrooms</label>
            <input type="number" min="0" name="bedrooms">
        </div>
        
        <div class="field">
            <label>Furnished</label>
            <select name="furnished_status">
                <option>Unfurnished</option>
                <option>Furnished</option>
                <option>Semi-Furnished</option>
            </select>
        </div>
        
        <div class="field full">
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>
        
        <div class="field">
            <label>Deposit information</label>
            <input name="deposit_info">
        </div>
        
        <div class="field">
            <label>Amenities</label>
            <input name="amenities" placeholder="WiFi, water, parking...">
        </div>
        
        <div class="field full">
            <label>Photos</label>
            <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
        </div>
        
        <div class="field">
            <label>Landlord name *</label>
            <input name="landlord_name" required>
        </div>
        
        <div class="field">
            <label>Landlord phone * <small>(private)</small></label>
            <input name="landlord_phone" required>
        </div>
        
        <div class="field">
            <label>Landlord email</label>
            <input type="email" name="landlord_email">
        </div>
        
        <div class="field">
            <label>Verification</label>
            <label><input type="checkbox" name="is_verified"> Publish as verified</label>
        </div>
        
        <div class="field full">
            <label>Private landlord notes</label>
            <textarea name="landlord_notes"></textarea>
        </div>
        
        <div class="field">
            <label><input type="checkbox" name="is_hidden"> Keep hidden from public</label>
        </div>
    </div>
    
    <button class="btn btn-primary">Save property</button>
    <a class="btn btn-muted" href="<?= appBaseUrl() ?>/admin/properties.php">Cancel</a>
</form>

<?php adminPageEnd(); ?>