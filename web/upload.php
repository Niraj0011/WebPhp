<?php
require_once 'config.php';
require_once 'db_connect.php';
$conn = db_connect();

// Define $uploadDir at the top to avoid undefined variable warning
$uploadDir = "uploads/"; // Relative to the script location (web/uploads/)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['upload']) && isset($_SESSION['user_id']) && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $fileName = basename($file['name']);
        $fileTmp = $file['tmp_name'];
        $filePath = $uploadDir . $fileName;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($fileTmp, $filePath)) {
            $user_id = $_SESSION['user_id'];
            $sql = "INSERT INTO uploads (user_id, file_path) VALUES ('$user_id', '$filePath')";
            if (mysqli_query($conn, $sql)) {
                $upload_message = "<p class='text-success text-center'>File uploaded successfully! <a href='?page=home' class='text-success'>Return to Home</a></p>";
            } else {
                $upload_message = "<p class='text-danger text-center'>Database error: " . mysqli_error($conn) . "</p>";
                unlink($filePath); // Clean up if database insert fails
            }
        } else {
            $upload_message = "<p class='text-danger text-center'>Error uploading file. Check permissions or file size. Error: " . print_r(error_get_last(), true) . "</p>";
        }
    } elseif (isset($_POST['delete']) && isset($_SESSION['user_id']) && isset($_POST['file_id'])) {
        $file_id = mysqli_real_escape_string($conn, $_POST['file_id']);
        $sql = "SELECT file_path FROM uploads WHERE id = '$file_id' AND user_id = '$_SESSION[user_id]' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $filePath = $row['file_path'];
            if (unlink($filePath)) {
                $sql = "DELETE FROM uploads WHERE id = '$file_id' AND user_id = '$_SESSION[user_id]'";
                mysqli_query($conn, $sql);
                $delete_message = "<p class='text-success text-center'>File deleted successfully!</p>";
            } else {
                $delete_message = "<p class='text-danger text-center'>Error deleting file from server.</p>";
            }
        } else {
            $delete_message = "<p class='text-danger text-center'>File not found or unauthorized.</p>";
        }
    }
}

$page_title = "Upload File";
?>
<?php require_once 'header.php'; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class='row justify-content-center'>
        <div class='col-md-6'>
            <div class='card glass-card shadow-lg border-0'>
                <div class='card-header bg-glass text-white text-center'>
                    <h2>Upload File</h2>
                </div>
                <div class='card-body p-4'>
                    <form method='POST' action='?page=upload' enctype='multipart/form-data'>
                        <div class='mb-3'>
                            <label for='file' class='form-label text-white'>Select File (JPEG, PDF, or DOCX)</label>
                            <input type='file' class='form-control glass-input' name='file' id='fileInput' accept='image/jpeg,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document' required>
                        </div>
                        <button type='submit' name='upload' class='btn btn-glass w-100 py-2'>Upload</button>
                    </form>
                    <?php if (isset($upload_message)) echo $upload_message; ?>
                    <div class='file-list mt-4'>
                        <h4 class='text-white'>Your Uploaded Files</h4>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT id, file_path FROM uploads WHERE user_id = '$user_id'";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $fileId = htmlspecialchars($row['id']);
                                $filePath = htmlspecialchars($row['file_path']);
                                $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                $relativePath = $uploadDir . basename($filePath); // Relative path
                                echo "<div class='file-preview mb-3'>";
                                if (in_array($fileExt, ['jpg', 'jpeg'])) {
                                    echo "<p class='text-white'><a href='/$relativePath' target='_blank'>JPEG: " . htmlspecialchars(basename($filePath)) . "</a></p>";
                                } elseif ($fileExt == 'pdf') {
                                    echo "<p class='text-white'><a href='/$relativePath' target='_blank'>PDF: " . htmlspecialchars(basename($filePath)) . "</a></p>";
                                } elseif ($fileExt == 'docx') {
                                    $googleDocsUrl = "https://docs.google.com/gview?url=" . urlencode("http://localhost/web/$relativePath") . "&embedded=false";
                                    echo "<p class='text-white'><a href='$googleDocsUrl' target='_blank'>DOCX: " . htmlspecialchars(basename($filePath)) . "</a></p>";
                                } else {
                                    echo "<p class='text-white-50'>Unsupported file type. <a href='/$relativePath' target='_blank' class='text-success'>" . htmlspecialchars(basename($filePath)) . "</a></p>";
                                }
                                echo "<form method='POST' action='?page=upload' style='display: inline;' onsubmit='return confirm(\"Are you sure you want to delete this file?\");'>
                                    <input type='hidden' name='file_id' value='$fileId'>
                                    <button type='submit' name='delete' class='btn btn-danger btn-sm mt-2'>Delete</button>
                                </form>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p class='text-center text-white-50'>No files uploaded yet.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <p class='text-center text-white'>Please <a href='?page=login' class='text-success'>login</a> to upload files.</p>
<?php endif; ?>

<?php mysqli_close($conn); ?>
<?php require_once 'footer.php'; ?>
