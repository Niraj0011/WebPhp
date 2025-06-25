<?php
require_once 'config.php';
require_once 'db_connect.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $is_admin = ($username === 'admin') ? 1 : 0;
    $remember_token = bin2hex(random_bytes(32));

    $sql = "INSERT INTO users (username, email, password, is_admin, remember_token) VALUES ('$username', '$email', '$password', '$is_admin', '$remember_token')";
    if (mysqli_query($conn, $sql)) {
        $user_id = mysqli_insert_id($conn);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = $is_admin;
        if (isset($_POST['remember_me'])) {
            setcookie('remember_me', $remember_token, time() + (30 * 24 * 60 * 60), "/");
        }
        header("Location: ?page=home");
        exit();
    }
}

$page_title = "Register";
?>
<?php require_once 'header.php'; ?>

<div class='row justify-content-center'>
    <div class='col-md-6'>
        <div class='card glass-card shadow-lg border-0'>
            <div class='card-header bg-glass text-white text-center'>
                <h2>Register</h2>
            </div>
            <div class='card-body p-4'>
                <form method='POST' action='?page=register'>
                    <div class='mb-3'>
                        <label for='username' class='form-label text-white'>Username</label>
                        <input type='text' class='form-control glass-input' name='username' required>
                    </div>
                    <div class='mb-3'>
                        <label for='email' class='form-label text-white'>Email</label>
                        <input type='email' class='form-control glass-input' name='email' required>
                    </div>
                    <div class='mb-4'>
                        <label for='password' class='form-label text-white'>Password</label>
                        <input type='password' class='form-control glass-input' name='password' required>
                    </div>
                    <div class='form-check mb-3'>
                        <input type='checkbox' class='form-check-input' name='remember_me' id='remember_me'>
                        <label class='form-check-label text-white' for='remember_me'>Remember Me</label>
                    </div>
                    <button type='submit' name='register' class='btn btn-glass w-100 py-2'>Register</button>
                </form>
                <p class='text-center mt-3'><a href='?page=login' class='text-success'>Already have an account? Login</a></p>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
<?php require_once 'footer.php'; ?>