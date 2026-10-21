<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../includes/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username dan password wajib diisi.'];
    header('Location: login.php');
    exit;
}

$maxAttempts = 5;
$lockDuration = 15 * 60;
$attemptKey = hash('sha256', strtolower($username));
$now = time();
$attempt = $_SESSION['login_attempts'][$attemptKey] ?? ['count' => 0, 'locked_until' => 0];

if ($attempt['locked_until'] > $now) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Terlalu banyak percobaan login gagal. Coba lagi dalam 15 menit.',
    ];
    header('Location: login.php');
    exit;
}

if ($attempt['locked_until'] > 0) {
    unset($_SESSION['login_attempts'][$attemptKey]);
    $attempt = ['count' => 0, 'locked_until' => 0];
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['login_attempts'][$attemptKey]);
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

$attempt['count']++;
if ($attempt['count'] >= $maxAttempts) {
    $attempt['locked_until'] = $now + $lockDuration;
    $_SESSION['login_attempts'][$attemptKey] = $attempt;
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Login gagal 5 kali berturut-turut. Akun ini dikunci sementara selama 15 menit.',
    ];
} else {
    $_SESSION['login_attempts'][$attemptKey] = $attempt;
    $remainingAttempts = $maxAttempts - $attempt['count'];
    $message = 'Username atau password salah.';
    if ($attempt['count'] >= 3) {
        $message .= ' Peringatan: tersisa ' . $remainingAttempts . ' percobaan sebelum login dikunci sementara.';
    }
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => $message];
}

header('Location: login.php');
exit;