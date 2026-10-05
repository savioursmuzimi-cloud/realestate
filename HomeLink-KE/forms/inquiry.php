<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$conn = getDBConnection();
$pid = (int)($_GET['property_id'] ?? $_POST['property_id'] ?? 0);
$p = $pid ? getProperty($conn, $pid) : null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    
    $name = post('name');
    $email = post('email');
    $subject = post('subject');
    $message = post('message');
    
    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please complete the required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } else {
        $stmt = $conn->prepare('INSERT INTO inquiries(name,email,subject,message,property_id) VALUES(?,?,?,?,?)');
        $stmt->bind_param('ssssi', $name, $email, $subject, $message, $pid);
        
        if ($stmt->execute()) {
            flash('success', 'Your inquiry has been sent to HomeLink-KE.');
            redirectTo($pid ? 'properties/details.php?id=' . $pid : 'index.php');
        } else {
            $error = 'Unable to send your inquiry.';
        }
        
        $stmt->close();
    }
}

$pageTitle = 'Contact HomeLink-KE | Inquiry';
require __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="form-wrap">
        <div class="kicker">CONTACT HOMELINK-KE</div>
        <h1>Send an inquiry</h1>
        
        <?php if ($p): ?>
            <p class="muted">Regarding: <strong><?= e($p['title']) ?></strong></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="property_id" value="<?= $pid ?>">
            
            <div class="form-grid">
                <div class="field">
                    <label>Name *</label>
                    <input name="name" required>
                </div>
                
                <div class="field">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="field full">
                    <label>Subject</label>
                    <input name="subject" value="<?= e($p ? 'Question about ' . $p['title'] : '') ?>">
                </div>
                
                <div class="field full">
                    <label>Message *</label>
                    <textarea name="message" required></textarea>
                </div>
            </div>
            
            <button class="btn btn-primary">Send inquiry</button>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>