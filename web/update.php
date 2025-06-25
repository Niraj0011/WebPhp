<?php
require_once 'config.php';
require_once 'db_connect.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload']) && isset($_SESSION['user_id']) && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $uploadDir = "uploads/";
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
        }
    } else {
        $upload_message = "<p class='text-danger text-center'>Error uploading file. Check permissions or file size.</p>";
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
                            <label for='file' class='form-label text-white'>Select File</label>
                            <input type='file' class='form-control glass-input' name='file' required>
                        </div>
                        <button type='submit' name='upload' class='btn btn-glass w-100 py-2'>Upload</button>
                    </form>
                    <?php if (isset($upload_message)) echo $upload_message; ?>
                    <div class='file-list mt-4'>
                        <h4 class='text-white'>Your Uploaded Files</h4>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT file_path FROM uploads WHERE user_id = '$user_id'";
                        $result = mysqli_query($conn, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $filePath = htmlspecialchars($row['file_path']);
                                $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                echo "<div class='file-preview mb-3'>";
                                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo "<img src='/$filePath' alt='Uploaded File' class='img-fluid'>";
                                } elseif ($fileExt == 'pdf') {
                                    echo "<object data='/$filePath' type='application/pdf'></object>";
                                } elseif ($fileExt == 'docx') {
                                    $googleDocsUrl = "https://docs.google.com/gview?url=" . urlencode("http://localhost/project/$filePath") . "&embedded=true";
                                    echo "<iframe src='$googleDocsUrl' frameborder='0'></iframe>";
                                } elseif (in_array($fileExt, ['txt', 'md'])) {
                                    $content = file_get_contents($filePath);
                                    echo "<pre class='bg-glass-inner p-2 rounded'>" . htmlspecialchars(substr($content, 0, 500)) . (strlen($content) > 500 ? "..." : "") . "</pre>";
                                } else {
                                    echo "<p class='text-white-50'>" . htmlspecialchars(basename($filePath)) . " (Preview not available for this file type)</p>";
                                }
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