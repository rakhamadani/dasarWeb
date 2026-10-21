<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'ID peminjaman tidak valid!'];
    header('Location: list.php');
    exit;
}

try {
    // Opsional: Jika ingin mengembalikan stok buku secara otomatis saat transaksi yang statusnya 'Dipinjam' dihapus
    $stmtCek = $pdo->prepare("SELECT * FROM peminjaman WHERE id = :id");
    $stmtCek->execute(['id' => $id]);
    $peminjaman = $stmtCek->fetch(PDO::FETCH_ASSOC);

    if ($peminjaman) {
        // Jika data yang dihapus masih berstatus 'Dipinjam', kita kembalikan stok bukunya
        if ($peminjaman['status'] === 'Dipinjam') {
            $pdo->beginTransaction();
            
            // Kembalikan stok buku
            $stmtUpdate = $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :buku_id");
            $stmtUpdate->execute(['buku_id' => $peminjaman['buku_id']]);

            // Hapus data peminjaman
            $stmtDel = $pdo->prepare("DELETE FROM peminjaman WHERE id = :id");
            $stmtDel->execute(['id' => $id]);

            $pdo->commit();
        } else {
            // Jika status sudah selesai/dikembalikan, langsung hapus saja
            $stmtDel = $pdo->prepare("DELETE FROM peminjaman WHERE id = :id");
            $stmtDel->execute(['id' => $id]);
        }
    }

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Riwayat peminjaman berhasil dihapus!'];
    header('Location: list.php');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menghapus data: ' . $e->getMessage()];
    header('Location: list.php');
    exit;
}