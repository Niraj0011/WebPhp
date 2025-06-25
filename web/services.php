<?php
require_once 'config.php';
$page_title = "Services";
?>
<?php require_once 'header.php'; ?>

<div class='card glass-card shadow-lg border-0'>
    <div class='card-body text-center py-5'>
        <h1 class='display-4 text-white'>Our Services</h1>
        <div class='row mt-4'>
            <div class='col-md-4'>
                <div class='card glass-card-inner shadow-sm border-0'>
                    <div class='card-body text-center p-3'>
                        <h5 class='card-title text-white'>File Management</h5>
                        <p class='card-text text-white-50'>Upload and manage your files securely with our platform.</p>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class='card glass-card-inner shadow-sm border-0'>
                    <div class='card-body text-center p-3'>
                        <h5 class='card-title text-white'>User Support</h5>
                        <p class='card-text text-white-50'>Get personalized support and assistance anytime.</p>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class='card glass-card-inner shadow-sm border-0'>
                    <div class='card-body text-center p-3'>
                        <h5 class='card-title text-white'>Custom Solutions</h5>
                        <p class='card-text text-white-50'>Tailored solutions to meet your specific needs.</p>
                    </div>
                </div>
            </div>
        </div>
        <p class='mt-4'><a href='?page=home' class='btn btn-glass btn-lg'>Back to Home</a></p>
    </div>
</div>

<?php require_once 'footer.php'; ?>