<?php
// 1. Guard keamanan: Wajib login
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

// 2. Ambil data anggota untuk dropdown
$stmtAnggota = $pdo->query("SELECT id, nama, no_anggota FROM anggota ORDER BY nama ASC");
$listAnggota = $stmtAnggota->fetchAll(PDO::FETCH_ASSOC);

// 3. Ambil data buku yang stoknya masih > 0 (Sesuai wireframe: buku stok habis tidak boleh dipilih)
$stmtBuku = $pdo->query("SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul ASC");
$listBuku = $stmtBuku->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Peminjaman Baru";
$base = "../"; // Menyesuaikan letak folder
include __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 600px; margin: 2rem auto; background: #ffffff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #eaeaea;">
    
    <h2 style="color: #2c3e50; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: 700;">Form Peminjaman Buku</h2>

    <!-- Tampilkan Flash Message jika ada error -->
    <?php if (isset($_SESSION['flash'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; border: 1px solid #f5c6cb;">
            <?php 
                echo $_SESSION['flash']['pesan']; 
                unset($_SESSION['flash']);
            ?>
        </div>
    <?php endif; ?>

    <form action="proses_tambah.php" method="POST">
        
        <div style="margin-bottom: 1.25rem;">
            <label for="anggota_id" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #34495e; font-size: 0.9rem;">Pilih Anggota</label>
            <select name="anggota_id" id="anggota_id" required 
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #dcdde1; border-radius: 6px; font-size: 1rem; background-color: #fff; outline: none;">
                <option value="">-- Pilih Anggota --</option>
                <?php foreach ($listAnggota as $a): ?>
                    <option value="<?php echo $a['id']; ?>">
                        <?php echo htmlspecialchars($a['nama'] . ' (' . $a['no_anggota'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 1.25rem;">
            <label for="buku_id" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #34495e; font-size: 0.9rem;">Pilih Buku (Stok > 0)</label>
            <select name="buku_id" id="buku_id" required 
                    style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #dcdde1; border-radius: 6px; font-size: 1rem; background-color: #fff; outline: none;">
                <option value="">-- Pilih Buku --</option>
                <?php foreach ($listBuku as $b): ?>
                    <option value="<?php echo $b['id']; ?>">
                        <?php echo htmlspecialchars($b['judul'] . ' (Stok: ' . $b['stok'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="tanggal_pinjam" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #34495e; font-size: 0.9rem;">Tanggal Pinjam</label>
            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="<?php echo date('Y-m-d'); ?>" readonly
                   style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #dcdde1; border-radius: 6px; font-size: 1rem; background-color: #f1f2f6; color: #7f8c8d; box-sizing: border-box;">
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" style="flex: 1; background-color: #137a18; color: white; border: none; padding: 0.75rem; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#137a18'" onmouseout="this.style.backgroundColor='#137a18'">
                Simpan Peminjaman
            </button>
            <a href="../index.php" style="flex: 1; text-align: center; background-color: #95a5a6; color: white; text-decoration: none; padding: 0.75rem; border-radius: 6px; font-size: 1rem; font-weight: 600; transition: background-color 0.2s;"
               onmouseover="this.style.backgroundColor='#7f8c8d'" onmouseout="this.style.backgroundColor='#95a5a6'">
                Batal
            </a>
        </div>

    </form>
</div>

<?php 
include __DIR__ . '/../includes/footer.php'; 
?>