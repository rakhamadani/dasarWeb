<?php
// 1. Guard keamanan: Wajib login
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

// 2. Ambil data transaksi peminjaman digabung dengan data anggota dan buku
$stmt = $pdo->query("
    SELECT peminjaman.*, anggota.nama AS nama_anggota, buku.judul AS judul_buku 
    FROM peminjaman 
    JOIN anggota ON peminjaman.anggota_id = anggota.id 
    JOIN buku ON peminjaman.buku_id = buku.id 
    ORDER BY peminjaman.id DESC
");
$listPeminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Daftar Peminjaman";
$base = "../";
include __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 950px; margin: 2rem auto; background: #ffffff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #eaeaea;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: #2c3e50; margin: 0; font-size: 1.5rem; font-weight: 700;">Daftar Peminjaman Buku</h2>
        <a href="tambah.php" style="background-color: #137a18; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: background-color 0.2s;"
           onmouseover="this.style.backgroundColor='#137a18'" onmouseout="this.style.backgroundColor='#137a18'">
            + Peminjaman Baru
        </a>
    </div>

    <!-- Tampilkan Flash Message jika ada -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="background-color: #d4edda; color: #155724; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid #c3e6cb;">
            <?php 
                echo $_SESSION['flash']['pesan']; 
                unset($_SESSION['flash']);
            ?>
        </div>
    <?php endif; ?>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #eaeaea; color: #34495e;">
                    <th style="padding: 0.75rem 1rem;">No</th>
                    <th style="padding: 0.75rem 1rem;">Anggota</th>
                    <th style="padding: 0.75rem 1rem;">Buku</th>
                    <th style="padding: 0.75rem 1rem;">Tanggal Pinjam</th>
                    <th style="padding: 0.75rem 1rem;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($listPeminjaman) > 0): ?>
                    <?php $no = 1; foreach ($listPeminjaman as $p): ?>
                        <tr style="border-bottom: 1px solid #eaeaea;">
                            <td style="padding: 0.75rem 1rem; color: #555;"><?php echo $no++; ?></td>
                            <td style="padding: 0.75rem 1rem; color: #2c3e50; font-weight: 500;"><?php echo htmlspecialchars($p['nama_anggota']); ?></td>
                            <td style="padding: 0.75rem 1rem; color: #2c3e50;"><?php echo htmlspecialchars($p['judul_buku']); ?></td>
                            <td style="padding: 0.75rem 1rem; color: #7f8c8d;"><?php echo htmlspecialchars($p['tanggal_pinjam']); ?></td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php if ($p['status'] === 'Dipinjam'): ?>
                                    <span style="background-color: #fff3cd; color: #856404; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Dipinjam</span>
                                <?php else: ?>
                                    <span style="background-color: #d4edda; color: #155724; padding: 0.25rem 0.6rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: center;">
                                <a href="hapus.php?id=<?php echo $p['id']; ?>" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat peminjaman ini?')"
                                   style="background-color: #e74c3c; color: white; padding: 0.35rem 0.75rem; border-radius: 4px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: background-color 0.2s;"
                                   onmouseover="this.style.backgroundColor='#c0392b'" onmouseout="this.style.backgroundColor='#e74c3c'">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: #7f8c8d;">Belum ada data transaksi peminjaman buku.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>