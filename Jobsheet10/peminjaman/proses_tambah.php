<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anggota_id = $_POST['anggota_id'] ?? '';
    $buku_id = $_POST['buku_id'] ?? '';
    $tanggal_pinjam = date('Y-m-d');

    // Validasi sederhana
    if (empty($anggota_id) || empty($buku_id)) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota dan Buku wajib dipilih!'];
        header('Location: tambah.php');
        exit;
    }

    try {
        // Mulai database transaction agar aman (jika stok gagal dikurangi, transaksi dibatalkan)
        $pdo->beginTransaction();

        // 1. Cek kembali stok buku saat ini
        $stmtCek = $pdo->prepare("SELECT stok FROM buku WHERE id = :id FOR UPDATE");
        $stmtCek->execute(['id' => $buku_id]);
        $buku = $stmtCek->fetch(PDO::FETCH_ASSOC);

        if (!$buku || $buku['stok'] <= 0) {
            throw new Exception("Maaf, stok buku tersebut sudah habis.");
        }

        // 2. Insert ke tabel peminjaman (Sesuaikan nama kolom tabel database Anda jika berbeda)
        $stmtInsert = $pdo->prepare("INSERT INTO peminjaman (anggota_id, buku_id, tanggal_pinjam, status) VALUES (:anggota_id, :buku_id, :tanggal_pinjam, 'Dipinjam')");
        $stmtInsert->execute([
            'anggota_id' => $anggota_id,
            'buku_id' => $buku_id,
            'tanggal_pinjam' => $tanggal_pinjam
        ]);

        // 3. Kurangi stok buku 1 buah
        $stmtUpdateStok = $pdo->prepare("UPDATE buku SET stok = stok - 1 WHERE id = :id");
        $stmtUpdateStok->execute(['id' => $buku_id]);

        // Selesaikan transaksi
        $pdo->commit();

        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Peminjaman berhasil dicatat!'];
        header('Location: ../index.php');
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal memproses peminjaman: ' . $e->getMessage()];
        header('Location: tambah.php');
        exit;
    }
} else {
    header('Location: tambah.php');
    exit;
}