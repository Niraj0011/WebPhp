<?php
require_once 'config.php';
require_once 'db_connect.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, is_admin, remember_token FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['is_admin'] = $row['is_admin'];
            if (isset($_POST['remember_me'])) {
                $remember_token = bin2hex(random_bytes(32));
                setcookie('remember_me', $remember_token, time() + (30 * 24 * 60 * 60), "/");
                $sql = "UPDATE users SET remember_token = '$remember_token' WHERE id = " . $row['id'];
                mysqli_query($conn, $sql);
            }
            header("Location: ?page=home");
            exit();
        } else {
            $login_message = "<p class='text-danger text-center'>Invalid username or password!</p>";
        }
    } else {
        $login_message = "<p class='text-danger text-center'>Invalid username or password!</p>";
    }
}

$page_title = "Login";
?>
<?php require_once 'header.php'; ?>

<div class='row justify-content-center'>
    <div class='col-md-6'>
        <div class='card glass-card shadow-lg border-0'>
            <div class='card-header bg-glass text-white text-center'>
                <h2>Login</h2>
            </div>
            <div class='card-body p-4'>
                <form method='POST' action='?page=login'>
                    <div class='mb-3'>
                        <label for='username' class='form-label text-white'>Username</label>
                        <input type='text' class='form-control glass-input' name='username' required>
                    </div>
                    <div class='mb-4'>
                        <label for='password' class='form-label text-white'>Password</label>
                        <input type='password' class='form-control glass-input' name='password' required>
                    </div>
                    <div class='form-check mb-3'>
                        <input type='checkbox' class='form-check-input' name='remember_me' id='remember_me'>
                        <label class='form-check-label text-white' for='remember_me'>Remember Me</label>
                    </div>
                    <button type='submit' name='login' class='btn btn-glass w-100 py-2'>Login</button>
                </form>
                <?php if (isset($login_message)) echo $login_message; ?>
                <p class='text-center mt-3'><a href='?page=register' class='text-success'>Don’t have an account? Register</a></p>
            </div>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
<?php require_once 'footer.php'; ?>