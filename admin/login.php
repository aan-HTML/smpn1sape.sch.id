<?php
session_start();
require_once '../includes/config.php';

// Kalau sudah login, langsung ke dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string(trim($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';

    $result = $conn->query("SELECT * FROM admin WHERE username = '$username'");

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        // BUG FIX: Dukung plain text (data lama) TAPI juga hash bcrypt.
        // Kalau password masih plain text, hash sekarang dan simpan ke DB.
        $valid = false;
        if (password_verify($password, $data['password'])) {
            $valid = true;
        } elseif ($password === $data['password']) {
            // Password masih plain text - hash dan update di DB
            $valid = true;
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $hashed_escaped = $conn->real_escape_string($hashed);
            $conn->query("UPDATE admin SET password='$hashed_escaped' WHERE id=" . (int)$data['id']);
        }

        if ($valid) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $data['id'];
            $_SESSION['admin_nama']      = $data['nama'];
            $_SESSION['last_activity']   = time();
            redirect('index.php');
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}

$timeout_msg = isset($_GET['timeout']) ? "Sesi Anda telah berakhir. Silakan login kembali." : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMPN 1 Sape</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-header">
            <img src="../assets/images/logo-sekolah.png" alt="Logo">
            <h2>Admin Panel</h2>
            <p>Silakan login untuk mengelola website</p>
        </div>
        <?php if($timeout_msg): ?>
        <div class="alert alert-warning"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($timeout_msg); ?></div>
        <?php endif; ?>
        <?php if($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus autocomplete="username">
            </div>
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary login-btn">MASUK SEKARANG</button>
        </form>
    </div>
</body>
</html>
