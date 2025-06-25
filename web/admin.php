<?php
require_once 'config.php';
require_once 'db_connect.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin']) && $_SESSION['is_admin']) {
    if (isset($_POST['delete_user'])) {
        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $sql = "DELETE FROM users WHERE id = '$user_id' AND id != $_SESSION[user_id]";
        mysqli_query($conn, $sql);
    } elseif (isset($_POST['delete_upload'])) {
        $upload_id = mysqli_real_escape_string($conn, $_POST['upload_id']);
        $sql = "SELECT file_path FROM uploads WHERE id = '$upload_id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            unlink($row['file_path']);
            $sql = "DELETE FROM uploads WHERE id = '$upload_id'";
            mysqli_query($conn, $sql);
        }
    }
}

$page_title = "Admin Panel";
?>
<?php require_once 'header.php'; ?>

<?php if (isset($_SESSION['user_id']) && $_SESSION['is_admin']): ?>
    <div class='card glass-card shadow-lg border-0'>
        <div class='card-header bg-glass text-white text-center'>
            <h1>Admin Panel</h1>
        </div>
        <div class='card-body p-4'>
            <h3 class='text-white'>Manage Users</h3>
            <table class='table table-striped table-hover glass-table'>
                <thead>
                    <tr>
                        <th class='text-white'>ID</th>
                        <th class='text-white'>Username</th>
                        <th class='text-white'>Email</th>
                        <th class='text-white'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, username, email FROM users";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td class='text-white'>" . htmlspecialchars($row['id']) . "</td>
                            <td class='text-white'>" . htmlspecialchars($row['username']) . "</td>
                            <td class='text-white'>" . htmlspecialchars($row['email'] ?? 'Not set') . "</td>
                            <td><form method='POST' action='?page=admin' onsubmit='return confirm(\"Are you sure?\");'><input type='hidden' name='user_id' value='" . htmlspecialchars($row['id']) . "'><button type='submit' name='delete_user' class='btn btn-danger btn-sm'>Delete</button></form></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h3 class='mt-4 text-white'>Manage Uploads</h3>
            <table class='table table-striped table-hover glass-table'>
                <thead>
                    <tr>
                        <th class='text-white'>ID</th>
                        <th class='text-white'>File Name</th>
                        <th class='text-white'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT id, file_path FROM uploads";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td class='text-white'>" . htmlspecialchars($row['id']) . "</td>
                            <td class='text-white'>" . htmlspecialchars(basename($row['file_path'])) . "</td>
                            <td><form method='POST' action='?page=admin' onsubmit='return confirm(\"Are you sure?\");'><input type='hidden' name='upload_id' value='" . htmlspecialchars($row['id']) . "'><button type='submit' name='delete_upload' class='btn btn-danger btn-sm'>Delete</button></form></td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <p class='text-center text-white'>You must be an admin to access this page. <a href='?page=login' class='text-success'>Login</a></p>
<?php endif; ?>

<?php mysqli_close($conn); ?>
<?php require_once 'footer.php'; ?>