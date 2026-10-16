<?php
require_once __DIR__ . '/../includes/admin.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$p = getProperty($conn, $id);
if (!$p) {
    flash('error', 'Property not found.');
    redirectTo('admin/properties.php');
}

// Remove one existing photo without deleting the property itself.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'remove_photo') {
    verifyCsrf();
    $photoId = (int)($_POST['photo_id'] ?? 0);
    $stmt = $conn->prepare('SELECT photo_path FROM property_photos WHERE id=? AND property_id=? LIMIT 1');
    $stmt->bind_param('ii', $photoId, $id);
    $stmt->execute();
    $photo = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$photo) {
        flash('error', 'Photo not found for this property.');
        redirectTo('admin/edit-property.php?id=' . $id);
    }
    $stmt = $conn->prepare('DELETE FROM property_photos WHERE id=? AND property_id=?');
    $stmt->bind_param('ii', $photoId, $id);
    $deleted = $stmt->execute();
    $stmt->close();
    if ($deleted) {
        $relativePath = ltrim(str_replace('\\', '/', (string)$photo['photo_path']), '/');
        // Only remove a physical file inside the known property photo directory.
        if (str_starts_with($relativePath, 'uploads/photos/')) {
            $filePath = __DIR__ . '/../' . $relativePath;
            if (is_file($filePath)) @unlink($filePath);
        }
        logAction($conn, "Removed photo #$photoId from property #$id");
        flash('success', 'Photo removed. The property remains intact.');
    } else {
        flash('error', 'Could not remove the photo.');
    }
    redirectTo('admin/edit-property.php?id=' . $id);
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $title = post('title');
    $location = post('location');
    $price = (float)($_POST['price'] ?? 0);
    $category = post('category');
    $status = post('status');
    $availability = post('availability');
    $description = post('description');
    $deposit = post('deposit_info');
    $bedrooms = $_POST['bedrooms'] === '' ? null : (int)$_POST['bedrooms'];
    $furnished = post('furnished_status');
    $amenities = post('amenities');
    $verified = isset($_POST['is_verified']) ? 1 : 0;
    $hidden = isset($_POST['is_hidden']) ? 1 : 0;
    $ln = post('landlord_name');
    $lp = post('landlord_phone');
    $le = post('landlord_email');
    $notes = post('landlord_notes');
    if ($title === '' || $location === '' || $price <= 0 || $category === '' || $ln === '' || $lp === '') $error = 'Required fields are missing.';
    else {
        $stmt = $conn->prepare('UPDATE properties SET title=?,location=?,price=?,category=?,status=?,availability=?,description=?,deposit_info=?,bedrooms=?,furnished_status=?,amenities=?,is_verified=?,is_hidden=?,landlord_name=?,landlord_phone=?,landlord_email=?,landlord_notes=? WHERE id=?');
        $stmt->bind_param('ssdsssssisiiissssi', $title, $location, $price, $category, $status, $availability, $description, $deposit, $bedrooms, $furnished, $amenities, $verified, $hidden, $ln, $lp, $le, $notes, $id);
        if ($stmt->execute()) {
            saveUploads($conn, $id, $_FILES['photos'] ?? []);
            logAction($conn, "Updated property #$id");
            flash('success', 'Property updated.');
            redirectTo('admin/properties.php');
        } else $error = 'Update failed: ' . $conn->error;
        $stmt->close();
    }
}
$p = getProperty($conn, $id);
$photos = getPropertyPhotos($conn, $id);
adminPageStart('Edit property', 'properties');
?>
<?php if ($error): ?>
    <div class="alert error"><?= e($error) ?></div>
<?php endif; ?>
<form class="admin-card" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="form-grid">
        <div class="field">
            <label>Property title *</label>
            <input name="title" value="<?= e($p['title']) ?>" required>
        </div>
        <div class="field">
            <label>Category *</label>
            <select name="category">
                <?php foreach (['Single Rooms', 'Bedsitters', '1 Bedroom', '2 Bedroom', 'Commercial', 'BNB', 'Other'] as $c): ?>
                    <option <?= ($p['category'] === $c ? 'selected' : '') ?>><?= e($c) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label>Public location *</label>
            <input name="location" value="<?= e($p['location']) ?>" required>
        </div>
        <div class="field">
            <label>Price *</label>
            <input type="number" step="0.01" name="price" value="<?= e((string)$p['price']) ?>" required>
        </div>
        <div class="field">
            <label>Status</label>
            <select name="status">
                <?php foreach (['available', 'reserved', 'rented', 'sold'] as $s): ?>
                    <option <?= ($p['status'] === $s ? 'selected' : '') ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label>Availability</label>
            <input name="availability" value="<?= e($p['availability']) ?>">
        </div>
        <div class="field">
            <label>Bedrooms</label>
            <input type="number" min="0" name="bedrooms" value="<?= e((string)$p['bedrooms']) ?>">
        </div>
        <div class="field">
            <label>Furnished</label>
            <select name="furnished_status">
                <?php foreach (['Unfurnished', 'Furnished', 'Semi-Furnished'] as $f): ?>
                    <option <?= ($p['furnished_status'] === $f ? 'selected' : '') ?>><?= $f ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field full">
            <label>Description</label>
            <textarea name="description"><?= e($p['description']) ?></textarea>
        </div>
        <div class="field">
            <label>Deposit</label>
            <input name="deposit_info" value="<?= e($p['deposit_info']) ?>">
        </div>
        <div class="field">
            <label>Amenities</label>
            <input name="amenities" value="<?= e($p['amenities']) ?>">
        </div>
        <div class="field full">
            <label>Add more photos</label>
            <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple>
        </div>
        <div class="field">
            <label>Landlord name *</label>
            <input name="landlord_name" value="<?= e($p['landlord_name']) ?>" required>
        </div>
        <div class="field">
            <label>Landlord phone *</label>
            <input name="landlord_phone" value="<?= e($p['landlord_phone']) ?>" required>
        </div>
        <div class="field">
            <label>Landlord email</label>
            <input type="email" name="landlord_email" value="<?= e($p['landlord_email']) ?>">
        </div>
        <div class="field">
            <label><input type="checkbox" name="is_verified" <?= $p['is_verified'] ? 'checked' : '' ?>> Verified</label>
        </div>
        <div class="field full">
            <label>Private notes</label>
            <textarea name="landlord_notes"><?= e($p['landlord_notes']) ?></textarea>
        </div>
        <div class="field">
            <label><input type="checkbox" name="is_hidden" <?= $p['is_hidden'] ? 'checked' : '' ?>> Hidden from public</label>
        </div>
    </div>
    <button class="btn btn-primary">Save changes</button>
    <a class="btn btn-muted" href="<?= appBaseUrl() ?>/admin/properties.php">Cancel</a>
</form>
<?php if ($photos): ?>
    <div class="admin-card" style="margin-top:15px">
        <h3>Existing photos</h3>
        <div class="file-list">
            <?php foreach ($photos as $ph): ?>
                <div class="existing-media-card">
                    <img src="<?= e(assetUrl($ph['photo_path'])) ?>" alt="Property photo" loading="lazy">
                    <form method="post" onsubmit="return confirm('Remove this photo from the property?')">
                        <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="photo_id" value="<?= (int)$ph['id'] ?>">
                        <input type="hidden" name="action" value="remove_photo">
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash" aria-hidden="true"></i> Remove photo</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php adminPageEnd(); ?>