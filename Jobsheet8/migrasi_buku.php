<?php
// Panggil file koneksi database
require __DIR__ . '/includes/koneksi.php';

// Tentukan path file JSON lama dari Jobsheet 6
$fileJson = __DIR__ . '/data/buku.json';

// Cek apakah file JSON-nya benar-benar ada
if (!file_exists($fileJson)) {
    die("File json tidak ditemukan!" . $fileJson);
}


$jsonKonten = file_get_contents($fileJson);


$daftarBukuLama = json_decode($jsonKonten, true);


if (empty($daftarBukuLama)) {
    die("Tidak ada data buku di dalam file JSON atau format JSON tidak valid.");
}

// Siapkan prepared statement untuk INSERT ke tabel buku
$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);

$jumlahBerhasil = 0;

// Lakukan perulangan untuk memasukkan setiap data buku ke database
foreach ($daftarBukuLama as $buku) {
    try {
        $stmt->execute([
            'judul'     => $buku['judul'] ?? '',
            'pengarang' => $buku['pengarang'] ?? '',
            'tahun'     => (int) ($buku['tahun'] ?? 0),
            'isbn'      => $buku['isbn'] ?? null,
            'stok'      => (int) ($buku['stok'] ?? 0),
            'kategori'  => $buku['kategori'] ?? null
        ]);
        $jumlahBerhasil++;
    } catch (PDOException $e) {
        echo "Gagal memasukkan buku '" . htmlspecialchars($buku['judul']) . "': " . $e->getMessage() . "<br>";
    }
}

echo "<h3>Migrasi Selesai!</h3>";
echo "<p>Berhasil memindahkan <strong>$jumlahBerhasil</strong> data buku dari file JSON ke database PostgreSQL.</p>";
echo '<a href="buku/list.php" class="btn btn-primary">Lihat Daftar Buku</a>';