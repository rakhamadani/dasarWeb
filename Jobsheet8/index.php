<?php
$page_title = "Beranda";
include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';

$totalBuku = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
?>

<body>
    <main>
        <section>
            <h2>Selamat Datang di Sistem Perpustakaan Mini</h2>
            <p>Aplikasi sederhana untuk mengelola koleksi buku dan anggota perpustakaan.</p>
        </section>

        <section>
            <h2>Ringkasan</h2>
            <article>
                <h3>Total Buku</h3>
                <p><?php echo $totalBuku; ?></p>
            </article>
            <article>
                <h3>Total Anggota</h3>
                <p><?php echo $totalAnggota; ?></p>
            </article>
            <article>
                <h3>Sedang Dipinjam</h3>
                <p><?php echo $_SESSION['sedang_dipinjam'] ?? 0; ?></p>
            </article>
            <article>
                <h3>Buku Terlambat</h3>
                <p><?php echo $_SESSION['buku_terlambat'] ?? 0; ?></p>
            </article>
            <div style="margin-top: 3rem;">
            <a href="reset.php" onclick="return confirm('Yakin ingin mengosongkan semua data session?')" style="color: #721c24; text-decoration: underline;">Reset Semua Data Session</a>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="assets/js/app.js"></script>
</body>

</html>