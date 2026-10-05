<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
s
$conn = getDBConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $name = post('full_name');
    $phone = post('phone');
    $email = post('email');
    $details = post('details');
    
    if ($name === '' || $phone === '' || $details === '') {
        $error = 'Please complete the required fields.';
    } else {
        $stmt = $conn->prepare('INSERT INTO complaints(full_name,phone,email,details) VALUES(?,?,?,?)');
        $stmt->bind_param('ssss', $name, $phone, $email, $details);
        
        if ($stmt->execute()) {
            flash('success', 'Your complaint has been received.');
            redirectTo('index.php');
        } else {
            $error = 'Unable to submit complaint.';
        }
        
        $stmt->close();
    }
}

$pageTitle = 'Complaint | HomeLink-KE';
require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="form-wrap">
        <div class="kicker">CUSTOMER SUPPORT</div>
        <h1>Make a complaint</h1>
        
        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            
            <div class="form-grid">
                <div class="field">
                    <label>Full name *</label>
                    <input name="full_name" required>
                </div>
                
                <div class="field">
                    <label>Phone *</label>
                    <input name="phone" required>
                </div>
                
                <div class="field full">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                
                <div class="field full">
                    <label>Complaint *</label>
                    <textarea name="details" required></textarea>
                </div>
            </div>
            
            <button class="btn btn-primary">Submit complaint</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>