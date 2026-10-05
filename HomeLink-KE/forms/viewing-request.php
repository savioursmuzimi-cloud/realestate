<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$conn = getDBConnection();
$propertyId = (int)($_GET['property_id'] ?? $_POST['property_id'] ?? 0);
$property = $propertyId ? getProperty($conn, $propertyId) : null;
$settings = getSiteSettings($conn);
$pageTitle = 'Request a Viewing | HomeLink-KE';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $name = post('full_name');
    $email = post('email');
    $phone = post('phone');
    $date = post('view_date');
    $notes = post('notes');
    
    if ($name === '' || $phone === '' || $date === '') {
        $error = 'Please fill in your name, phone number and preferred viewing date.';
    } elseif ($propertyId && !$property) {
        $error = 'The selected property is no longer available.';
    } else {
        $stmt = $conn->prepare('INSERT INTO viewing_requests(property_id,full_name,email,phone,view_date,notes,status) VALUES(?,?,?,?,?,?,?)');
        $status = 'pending';
        $stmt->bind_param('issssss', $propertyId, $name, $email, $phone, $date, $notes, $status);
        
        if ($stmt->execute()) {
            logAction($conn, 'New viewing request submitted for property #' . $propertyId);
            flash('success', 'Your viewing request has been received. HomeLink-KE will contact you.');
            redirectTo('properties/details.php?id=' . $propertyId);
        } else {
            $error = 'We could not save your request. Please try again.';
        }
        
        $stmt->close();
    }
}

require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="form-wrap">
        <div class="kicker">VIEWING REQUEST</div>
        <h1>Request a viewing</h1>
        
        <?php if ($property): ?>
            <p class="muted">For: <strong><?= e($property['title']) ?></strong> — <?= e($property['location']) ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="property_id" value="<?= $propertyId ?>">
            
            <div class="form-grid">
                <div class="field">
                    <label>Full name *</label>
                    <input name="full_name" required>
                </div>
                
                <div class="field">
                    <label>Phone *</label>
                    <input name="phone" required placeholder="0712 345 678">
                </div>
                
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                
                <div class="field">
                    <label>Preferred date *</label>
                    <input type="date" name="view_date" min="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="field full">
                    <label>Notes</label>
                    <textarea name="notes" placeholder="Preferred time, questions, or anything we should know"></textarea>
                </div>
            </div>
            
            <button class="btn btn-primary">Submit viewing request</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>