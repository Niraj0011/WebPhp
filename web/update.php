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

$page_title = "Home";
?>
<?php require_once 'header.php'; ?>

<?php if (!isset($_SESSION['user_id'])): ?>
    <div class='card glass-card shadow-lg border-0'>
        <div class='card-body text-center py-5'>
            <h1 class='display-4 text-white'>Welcome to Our Website</h1>
            <p class='lead home-unlogged-text'>Please <a href='?page=register' class='text-success'>register</a> or <a href='?page=login' class='text-success'>login</a> to continue.</p>
            <p class='text-white-50 mt-4'>Last updated: 11:18 PM +0545, June 25, 2025</p>
        </div>
    </div>
<?php else: ?>
    <div class='card glass-card shadow-lg border-0'>
        <div class='card-body text-center py-5'>
            <h1 class='display-4 text-white'>Welcome to Our Website</h1>
            <p class='lead'>Welcome, <span class='text-success'><?php echo htmlspecialchars($_SESSION['username']); ?>!</span></p>
            <h3 class='mt-4 text-white'>Your Profile</h3>
            <?php
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT username, email, registered_at FROM users WHERE id = '$user_id'";
            $result = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card glass-card-inner shadow-sm mb-4'><div class='card-body'><p class='mb-1 text-white'><strong>Username:</strong> " . htmlspecialchars($row['username']) . "</p><p class='mb-1 text-white'><strong>Email:</strong> " . htmlspecialchars($row['email'] ?? 'Not set') . "</p><p class='text-white'><strong>Registered Since:</strong> " . htmlspecialchars($row['registered_at']) . "</p></div></div>";
            }
            ?>
            <h3 class='mt-4 text-white'>Your Uploaded Files</h3>
            <div class='row'>
                <?php
                $sql = "SELECT file_path FROM uploads WHERE user_id = '$user_id'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $filePath = htmlspecialchars($row['file_path']);
                        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        $uploadDir = "uploads/";
                        $relativePath = $uploadDir . basename($filePath);
                        echo "<div class='col-md-4 mb-3'><div class='card glass-card-inner shadow-sm'><div class='card-body text-center'>";
                        if (in_array($fileExt, ['jpg', 'jpeg'])) {
                            echo "<a href='javascript:void(0)' onclick=\"window.open('/web/$relativePath', '_blank')\"><p class='text-white'>JPEG: " . htmlspecialchars(basename($filePath)) . "</p></a>";
                        } elseif ($fileExt == 'pdf') {
                            echo "<a href='javascript:void(0)' onclick=\"window.open('/web/$relativePath', '_blank')\"><p class='text-white'>PDF: " . htmlspecialchars(basename($filePath)) . "</p></a>";
                        } elseif ($fileExt == 'docx') {
                            $googleDocsUrl = "https://docs.google.com/gview?url=" . urlencode("http://localhost/web/$relativePath") . "&embedded=false";
                            echo "<a href='javascript:void(0)' onclick=\"window.open('$googleDocsUrl', '_blank')\"><p class='text-white'>DOCX: " . htmlspecialchars(basename($filePath)) . "</p></a>";
                        } else {
                            echo "<p class='text-white-50'>Unsupported file type. <a href='/web/$relativePath' target='_blank' class='text-success'>" . htmlspecialchars(basename($filePath)) . "</a></p>";
                        }
                        echo "</div></div></div>";
                    }
                } else {
                    echo "<p class='text-center text-white-50'>No files uploaded yet.</p>";
                }
                ?>
            </div>
            <p class='text-white-50 mt-4'>Last updated: 11:18 PM +0545, June 25, 2025</p>
        </div>
    </div>
<?php endif; ?>

<?php mysqli_close($conn); ?>
<?php require_once 'footer.php'; ?>
