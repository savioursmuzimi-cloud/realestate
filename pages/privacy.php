<?php
$pageTitle = 'Privacy Policy';
require __DIR__ . '/../includes/header.php';
?>
<div class="back-navigation">
    <button type="button" class="back-button" onclick="goBack()">
        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
    </button>
</div>
<section class="section">
    <div class="container">
        <div class="form-wrap">
            <div class="kicker">PRIVACY</div>
            <h1>Privacy Policy</h1>
            <p>HomeLink-KE collects information you voluntarily provide through enquiries, viewing requests, complaints, landlord requests and BnB requests.</p>
            <h3>How we use it</h3>
            <p>We use submitted information to respond to you, arrange property viewings or BnB stays, manage requests and provide customer support. Landlord phone numbers and other private details are not displayed publicly.</p>
            <h3>Data handling</h3>
            <p>We do not sell submitted personal information. Information is retained only as reasonably necessary for business operations, records and support.</p>
        </div>
    </div>
</section>
<script>
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