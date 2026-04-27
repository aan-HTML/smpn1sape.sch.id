<?php
require_once '../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama'] ?? ''));
    $email = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $pesan = $conn->real_escape_string(trim($_POST['pesan'] ?? ''));
    if ($nama && $email && $pesan) {
        $conn->query("INSERT INTO pesan (nama, email, pesan, created_at) VALUES ('$nama', '$email', '$pesan', NOW())");
    }
}
header('Location: ../index.php#contact');
exit;
?>
