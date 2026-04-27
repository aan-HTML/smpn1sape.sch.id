<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smpn1sape');

// Koneksi Database
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}


function clean_input($data) {
    global $conn;
    if (is_null($data)) return "";
    $data = trim($data);
    $data = stripslashes($data);
    return $conn->real_escape_string($data);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function alert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type'    => $type
    ];
}

function show_alert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        $safe_type    = htmlspecialchars($alert['type'], ENT_QUOTES, 'UTF-8');
        $safe_message = htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8');
        echo '<div class="alert alert-' . $safe_type . '"><i class="fas fa-info-circle"></i> ' . $safe_message . '</div>';
        unset($_SESSION['alert']);
    }
}

// Fungsi untuk generate slug
function generate_slug($string) {
    $map = ['à'=>'a','á'=>'a','â'=>'a','ä'=>'a','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e',
            'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ò'=>'o','ó'=>'o','ô'=>'o','ö'=>'o',
            'ù'=>'u','ú'=>'u','û'=>'u','ü'=>'u','ñ'=>'n','ç'=>'c'];
    $string = strtr(strtolower($string), $map);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', trim($string));
    return trim($string, '-');
}

// Fungsi untuk upload file
function upload_file($file, $folder = 'uploads/') {
    $target_dir = "../assets/images/" . $folder;

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $allowed_extensions = ["jpg", "jpeg", "png", "gif"];
    $allowed_mimes      = ["image/jpeg", "image/png", "image/gif"];

    if (!in_array($file_extension, $allowed_extensions)) {
        return ['success' => false, 'message' => 'Format file tidak diizinkan. Gunakan JPG, PNG, atau GIF.'];
    }

    // Validasi MIME type sesungguhnya (bukan hanya ekstensi)
    $finfo     = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file["tmp_name"]);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_mimes)) {
        return ['success' => false, 'message' => 'File bukan gambar yang valid.'];
    }

    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => 'Ukuran file terlalu besar (max 5MB)'];
    }

    $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
    $target_file  = $target_dir . $new_filename;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => $folder . $new_filename];
    } else {
        return ['success' => false, 'message' => 'Gagal upload file'];
    }
}

// Fungsi untuk delete file
function delete_file($filename) {
    $file_path = "../assets/images/" . $filename;
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_verify() {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        die('Akses tidak sah. Silakan kembali dan coba lagi.');
    }
}

// Set timezone
date_default_timezone_set('Asia/Makassar');

// Fungsi helper global untuk gambar thumbnail
function get_thumb($filename, $default = 'mural.jpg') {
    $path = '../assets/images/';
    return (!empty($filename)) ? $path . $filename : $path . $default;
}

// Fungsi helper global untuk ringkasan teks
function get_excerpt($html, $limit = 100) {
    $text = strip_tags($html);
    if (strlen($text) <= $limit) return $text;
    return substr($text, 0, $limit) . '...';
}
