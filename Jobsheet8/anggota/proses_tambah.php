<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

// Ambil data dari form (sesuaikan dengan implementasimu)
$nama = $_POST['nama'] ?? '';
$no_anggota = $_POST['no_anggota'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$no_hp = $_POST['no_hp'] ?? '';

try {
    // Siapkan prepared statement untuk INSERT data anggota
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp)
         VALUES (:nama, :no_anggota, :alamat, :no_hp)"
    );

    // Eksekusi query dengan array data
    $stmt->execute([
        'nama' => $nama,
        'no_anggota' => $no_anggota,
        'alamat' => $alamat,
        'no_hp' => $no_hp
    ]);

    // Jika berhasil, buat flash message sukses dan redirect ke halaman list
    $_SESSION['flash'] = [
        'type' => 'success', 
        'pesan' => 'Anggota berhasil ditambahkan.'
    ];
    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    // Tangkap error PostgreSQL (Kode error '23505' adalah kode standar untuk unique_violation)
    if ($e->getCode() == '23505') {
        $_SESSION['flash'] = [
            'type' => 'danger', 
            'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'
        ];
    } else {
        // Tangkap error PDO lainnya jika terjadi masalah database lain
        $_SESSION['flash'] = [
            'type' => 'danger', 
            'pesan' => 'Terjadi kesalahan pada database: ' . $e->getMessage()
        ];
    }

    // Redirect kembali ke form tambah supaya pengguna bisa memperbaiki input
    header('Location: tambah.php');
    exit;
}