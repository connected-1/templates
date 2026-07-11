<?php

$uploadDir = __DIR__ . "/uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

/* ---------- Download ---------- */
if (isset($_GET["download"])) {

    $filename = basename($_GET["download"]);
    $file = $uploadDir . $filename;

    if (!file_exists($file)) {
        http_response_code(404);
        exit("File not found");
    }

    header("Content-Type: application/octet-stream");
    header("Content-Length: " . filesize($file));
    header("Content-Disposition: attachment; filename=\"$filename\"");

    readfile($file);
    exit;
}

/* ---------- Upload ---------- */
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == UPLOAD_ERR_OK) {

        $filename = basename($_FILES["file"]["name"]);
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination)) {
            $message = "Upload successful! File: " . htmlspecialchars($filename);
        } else {
            $message = "Upload failed.";
        }

    } else {
        $message = "No file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
</head>
<body>

<h2>Upload File</h2>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <br><br>
    <button type="submit">Upload</button>
</form>

</body>
</html>
