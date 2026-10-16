<?php 
$pageTitle = 'Terms & Conditions';
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
            <div class="kicker">TERMS</div>
            <h1>Terms & Conditions</h1>

            <h3>Listings</h3>
            <p>Listings are subject to availability and verification. A public listing does not guarantee that a property remains available until a viewing and agreement are completed.</p>

            <h3>Viewings</h3>
            <p>A viewing request is a request for HomeLink-KE to arrange a viewing. It is not a lease, reservation or guarantee of availability.</p>

            <h3>Payments</h3>
            <p>Clients should physically view and agree on the property before paying rent or deposit. Rent and deposit are paid directly to the landlord. HomeLink-KE service fees are separate.</p>

            <hr style="margin: 30px 0; border: 0; border-top: 1px solid var(--border, #e5e7eb);">

            <div class="kicker">DISCLAIMER</div>
            <h2>Important information</h2>
            <p>HomeLink-KE acts as an intermediary connecting clients with property owners and BnB operators. Verification reduces risk but does not replace the client's own inspection and due diligence.</p>
            <p>Always view a property or stay, confirm the terms, and verify payment details before transferring money.</p>
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