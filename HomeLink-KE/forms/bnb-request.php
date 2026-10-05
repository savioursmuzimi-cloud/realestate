<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$conn = getDBConnection();
$propertyId = (int)($_GET['property_id'] ?? $_POST['property_id'] ?? 0);
$property = $propertyId ? getProperty($conn, $propertyId) : null;
$pageTitle = 'BnB Request | HomeLink-KE';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $name = post('full_name');
    $phone = post('phone');
    $email = post('email');
    $checkIn = post('check_in');
    $checkOut = post('check_out');
    $guests = max(1, (int)($_POST['guests'] ?? 1));
    $notes = post('notes');
    
    if (!$property || strtolower((string)$property['category']) !== 'bnb') {
        $error = 'The selected BnB is unavailable.';
    } elseif ($name === '' || $phone === '' || $checkIn === '' || $checkOut === '') {
        $error = 'Please complete the required fields.';
    } elseif ($checkOut <= $checkIn) {
        $error = 'Check-out must be after check-in.';
    } else {
        $stmt = $conn->prepare('INSERT INTO bnb_requests(property_id,full_name,email,phone,check_in,check_out,guests,notes,status) VALUES(?,?,?,?,?,?,?,?,?)');
        $status = 'pending';
        $stmt->bind_param('isssssiss', $propertyId, $name, $email, $phone, $checkIn, $checkOut, $guests, $notes, $status);
        
        if ($stmt->execute()) {
            logAction($conn, 'New BnB request submitted for property #' . $propertyId);
            flash('success', 'Your BnB request has been received. HomeLink-KE will contact you to confirm availability.');
            redirectTo('properties/details.php?id=' . $propertyId);
        } else {
            $error = 'We could not save your request.';
        }
        
        $stmt->close();
    }
}

require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="form-wrap">
        <div class="kicker">BNB REQUEST</div>
        <h1>Request this BnB</h1>
        
        <?php if ($property): ?>
            <p class="muted">
                <strong><?= e($property['title']) ?></strong> — <?= e($property['location']) ?> — <?= formatKSH($property['price']) ?> / night
            </p>
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
                    <input name="phone" required>
                </div>
                
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                
                <div class="field">
                    <label>Guests *</label>
                    <input type="number" min="1" max="30" name="guests" value="1" required>
                </div>
                
                <div class="field">
                    <label>Check-in *</label>
                    <input type="date" name="check_in" min="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="field">
                    <label>Check-out *</label>
                    <input type="date" name="check_out" min="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="field full">
                    <label>Notes</label>
                    <textarea name="notes" placeholder="Arrival time or special request"></textarea>
                </div>
            </div>
            
            <button class="btn btn-primary">Send BnB request</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>