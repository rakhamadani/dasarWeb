<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Di dalam proses_login.php, setelah verifikasi password berhasil ($user && password_verify(...))
if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['role'] = $user['role'];

    // Cek apakah opsi "Ingat Saya" dicentang
    if (isset($_POST['remember'])) {
        // Buat token acak yang aman
        $token = bin2hex(random_bytes(32));
        
        // Simpan token ke database untuk user ini
        $updateToken = $pdo->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $updateToken->execute(['token' => $token, 'id' => $user['id']]);

        // Set cookie berlaku selama 30 hari (30 * 24 * 60 * 60 detik)
        setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true);
    }

    header('Location: ../index.php');
    exit;
}