<?php
$pageTitle = 'About HomeLink-KE';
require __DIR__ . '/../includes/header.php';
?>
<div class="back-navigation">
    <button type="button" class="back-button" onclick="goBack()">
        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
    </button>
</div>
<section class="section">
    <div class="container">
        <div class="form-wrap" style="max-width:900px">
            <div class="kicker">ABOUT US</div>
            <h1>HomeLink-KE is the intermediary.</h1>
            <p>HomeLink-KE helps clients find verified rental homes, commercial spaces and BnB stays in Kisii while keeping the process organised through one point of contact.</p>
            <h2>How the model works</h2>
            <p>Property owners submit their property information to HomeLink-KE. We verify and publish suitable listings. Clients contact HomeLink-KE for questions and viewings rather than receiving the landlord's private number from the website.</p>
            <p>After a client views a property and agrees to rent, rent and deposit are paid directly to the landlord. HomeLink-KE charges its applicable service fee for the intermediary service.</p>
            <h2>What we focus on</h2>
            <ul>
                <li>Accurate, verified listings</li>
                <li>Private landlord contact information</li>
                <li>Clear viewing and enquiry processes</li>
                <li>Simple BnB booking requests</li>
            </ul>
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