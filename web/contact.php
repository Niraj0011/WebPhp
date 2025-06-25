<?php
require_once 'config.php';
require_once 'db_connect.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contact']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO contacts (user_id, name, email, message) VALUES ('$user_id', '$name', '$email', '$message')";
    if (mysqli_query($conn, $sql)) {
        $contact_message = "<p class='text-success text-center'>Message sent successfully! <a href='?page=home' class='text-success'>Return to Home</a></p>";
    } else {
        $contact_message = "<p class='text-danger text-center'>Error: " . mysqli_error($conn) . "</p>";
    }
}

$page_title = "Contact";
?>
<?php require_once 'header.php'; ?>

<div class='row justify-content-center'>
    <div class='col-md-6'>
        <div class='card glass-card shadow-lg border-0'>
            <div class='card-header bg-glass text-white text-center'>
                <h2>Contact Us</h2>
            </div>
            <div class='card-body p-4'>
                <form method='POST' action='?page=contact'>
                    <div class='mb-3'>
                        <label for='name' class='form-label text-white'>Name</label>
                        <input type='text' class='form-control glass-input' name='name' required>
                    </div>
                    <div class='mb-3'>
                        <label for='email' class='form-label text-white'>Email</label>
                        <input type='email' class='form-control glass-input' name='email' required>
                    </div>
                    <div class='mb-4'>
                        <label for='message' class='form-label text-white'>Message</label>
                        <textarea class='form-control glass-input' name='message' rows='4' required></textarea>
                    </div>
                    <button type='submit' name='contact' class='btn btn-glass w-100 py-2'>Send</button>
                </form>
                <?php if (isset($contact_message)) echo $contact_message; ?>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
<?php require_once 'footer.php'; ?>